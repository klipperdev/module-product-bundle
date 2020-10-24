<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Price;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Klipper\Component\DoctrineExtra\Util\ManagerUtils;
use Klipper\Module\ProductBundle\Choice\ProductListRuleAppliedOn;
use Klipper\Module\ProductBundle\Model\PriceListInterface;
use Klipper\Module\ProductBundle\Model\PriceListRuleInterface;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PriceManager implements PriceManagerInterface
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getProductPrice($product, $productCombination = null, $priceList = null, int $quantity = 0): float
    {
        $em = ManagerUtils::getRequiredManager($this->doctrine, ProductInterface::class);

        if (!$product instanceof ProductInterface) {
            $product = $em->getRepository(ProductInterface::class)->findOneBy(['id' => $product]);
        }

        if (null !== $productCombination && !$productCombination instanceof ProductCombinationInterface) {
            $productCombination = $em->getRepository(ProductCombinationInterface::class)->findOneBy([
                'id' => $productCombination,
                'product' => $product,
            ]);
        }

        // Force to return default value if product in argument is not the same that in the product combination
        if (null === $product
            || (null !== $productCombination && $productCombination->getProductId() !== $product->getId())
        ) {
            return 0.0;
        }

        return null !== $priceList
            ? $this->getProductPriceByPriceList($priceList, $product, $productCombination, $quantity)
            : $this->getProductPriceByProduct($product, $productCombination);
    }

    private function getProductPriceByProduct(
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null
    ): float {
        if (null !== $productCombination && null !== $productCombinationPrice = $productCombination->getPrice()) {
            return $productCombinationPrice;
        }

        return null !== $product && null !== $product->getPrice()
            ? $product->getPrice()
            : 0.0;
    }

    /**
     * @param int|PriceListInterface|string $priceList The instance or id of the price list
     */
    private function getProductPriceByPriceList(
        $priceList,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0
    ): float {
        $priceListId = $priceList instanceof PriceListInterface
            ? $priceList->getId()
            : $priceList;
        $productId = $product instanceof ProductInterface
            ? $product->getId()
            : $product;
        $productCombinationId = $productCombination instanceof ProductCombinationInterface
            ? $productCombination->getId()
            : $productCombination;
        $productRangeId = null !== $product->getProductRange()
            ? $product->getProductRange()->getId()
            : null;

        $rules = $this->getRules($priceListId, $quantity);

        foreach ($rules as $rule) {
            if ($this->isValidRule($rule, $productId, $productCombinationId, $productRangeId)) {
                return $this->getPriceFromRule($rule, $product, $productCombination, $quantity);
            }
        }

        return 0.0;
    }

    /**
     * @param int|string $priceListId The id of price list
     *
     * @return PriceListRuleInterface[]
     */
    private function getRules($priceListId, int $minimumQuantity): array
    {
        $em = ManagerUtils::getRequiredManager($this->doctrine, PriceListRuleInterface::class);
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(PriceListRuleInterface::class);
        $orderedRules = [];

        /** @var PriceListRuleInterface[] $rules */
        $rules = $repo->createQueryBuilder('plr')
            ->select('plr')
            ->where('plr.priceList = :priceListId')
            ->andWhere('plr.startAt is NULL OR plr.startAt <= :now')
            ->andWhere('plr.endAt is NULL OR plr.endAt >= :now')
            ->andWhere('plr.minimumQuantity is NULL OR plr.minimumQuantity >= :minimumQuantity')
            ->setParameter('priceListId', $priceListId)
            ->setParameter('now', new \DateTime())
            ->setParameter('minimumQuantity', $minimumQuantity)
            ->getQuery()
            ->getResult()
        ;

        $appliedOns = array_reverse(ProductListRuleAppliedOn::getValues());
        $mapRules = [];

        foreach ($rules as $rule) {
            $mapRules[$rule->getAppliedOn()][] = $rule;
        }

        foreach ($appliedOns as $appliedOn) {
            if (isset($mapRules[$appliedOn])) {
                $orderedRules = array_merge($orderedRules, $mapRules[$appliedOn]);
            }
        }

        return array_values($orderedRules);
    }

    /**
     * @param int|string      $productId
     * @param null|int|string $productCombinationId
     * @param null|int|string $productRangeId
     */
    private function isValidRule(PriceListRuleInterface $rule, $productId, $productCombinationId, $productRangeId): bool
    {
        switch ($rule->getAppliedOn()) {
            case 'product_combination':
                if (null === $productCombinationId) {
                    return false;
                }

                return null !== $rule->getProductCombination() && $productCombinationId === $rule->getProductCombination()->getId();
            case 'product':
                return null !== $rule->getProduct() && $productId === $rule->getProduct()->getId();
            case 'product_range':
                return null !== $productRangeId && null !== $rule->getProductRange() && $productRangeId === $rule->getProductRange()->getId();
            case 'all_products':
                return true;
            default:
                return false;
        }
    }

    /**
     * @param int|PriceListInterface|string $priceList
     */
    private function getPriceFromRule(
        PriceListRuleInterface $rule,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0
    ): float {
        switch ($rule->getPriceCalculation()) {
            case 'flat_rate':
                return (float) $rule->getValue();
            case 'percent':
                $productPrice = (float) $product->getPrice();
                $rate = (float) $rule->getValue();

                return (float) $productPrice * $rate;
            case 'formula':
                return $this->getPriceFromRuleFormula($rule, $product, $productCombination, $quantity);
            default:
                return 0.0;
        }
    }

    private function getPriceFromRuleFormula(
        PriceListRuleInterface $rule,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0
    ): float {
        switch ($rule->getFormulaBasedOn()) {
            case 'selling_price':
                $price = null !== $productCombination
                    ? (float) $productCombination->getPrice() : (float) $product->getPrice();

                break;
            case 'other_price_list':
                $price = null !== $rule->getFormulaPriceList()
                    ? $this->getProductPrice($product, $productCombination, $rule->getFormulaPriceList(), $quantity)
                    : 0.0;

                break;
            default:
                $price = 0.0;
        }

        $roundedMethod = (float) $rule->getFormulaRoundedMethod();
        $minMarge = (float) $rule->getFormulaMinimumMargin();
        $maxMarge = (float) $rule->getFormulaMaximumMargin();
        $newPrice = $price - ($price * (float) $rule->getFormulaPriceReduction()) + (float) $rule->getFormulaMargin();

        if ($minMarge > 0 && $newPrice < ($price + $minMarge)) {
            $newPrice = $price + $minMarge;
        }

        if ($maxMarge > 0 && $newPrice > ($price + $maxMarge)) {
            $newPrice = $price + $maxMarge;
        }

        return $roundedMethod > 0
            ? PriceUtil::round($newPrice, $roundedMethod)
            : $newPrice;
    }
}
