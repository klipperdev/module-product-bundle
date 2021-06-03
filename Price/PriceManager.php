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

use Doctrine\Persistence\ManagerRegistry;
use Klipper\Component\DoctrineExtra\Util\ManagerUtils;
use Klipper\Module\ProductBundle\Exception\InvalidArgumentException;
use Klipper\Module\ProductBundle\Model\PriceListInterface;
use Klipper\Module\ProductBundle\Model\PriceListRuleInterface;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;
use Klipper\Module\ProductBundle\Model\ProductRangeInterface;
use Klipper\Module\ProductBundle\Repository\PriceListRuleRepositoryInterface;

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

    public static function isExtraPrice(
        PriceListRuleInterface $rule,
        ProductInterface $product
    ): bool {
        return $rule->isExtra() || $product->isExtra();
    }

    public function getProductPrice(
        $product,
        $productCombination = null,
        $priceList = null,
        int $quantity = 0,
        $dependingOnProduct = null,
        $dependingOnProductCombination = null,
        $dependingOnProductRange = null
    ): Price {
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
            return new Price(0.0);
        }

        return null !== $priceList
            ? $this->getProductPriceByPriceList($priceList, $product, $productCombination, $quantity, $dependingOnProduct, $dependingOnProductCombination, $dependingOnProductRange)
            : $this->getProductPriceByProduct($product, $productCombination);
    }

    private function getProductPriceByProduct(
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null
    ): Price {
        if (null !== $productCombination && null !== $productCombinationPrice = $productCombination->getPrice()) {
            return new Price($productCombinationPrice, $product->isExtra());
        }

        return null !== $product->getPrice()
            ? new Price($product->getPrice(), $product->isExtra())
            : new Price(0.0);
    }

    /**
     * @param int|PriceListInterface|string $priceList The instance or id of the price list
     */
    private function getProductPriceByPriceList(
        $priceList,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0,
        ?ProductInterface $dependingOnProduct = null,
        ?ProductCombinationInterface $dependingOnProductCombination = null,
        ?ProductRangeInterface $dependingOnProductRange = null
    ): Price {
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
        $dependingOnProductId = $dependingOnProduct instanceof ProductInterface
            ? $dependingOnProduct->getId()
            : $dependingOnProduct;
        $dependingOnProductCombinationId = $dependingOnProductCombination instanceof ProductCombinationInterface
            ? $dependingOnProductCombination->getId()
            : $dependingOnProductCombination;
        $dependingOnProductRangeId = null !== $dependingOnProductRange
            ? $dependingOnProductRange->getId()
            : $dependingOnProductRange;

        $rules = $this->getRules($priceListId, $quantity);

        foreach ($rules as $rule) {
            if ($this->isValidRule($rule, $productId, $productCombinationId, $productRangeId, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId)) {
                return $this->getPriceFromRule($rule, $product, $productCombination, $quantity, $dependingOnProduct, $dependingOnProductCombination, $dependingOnProductRange);
            }
        }

        return new Price(0.0, $product->isExtra());
    }

    /**
     * @param int|string $priceListId The id of price list
     *
     * @return PriceListRuleInterface[]
     */
    private function getRules($priceListId, int $minimumQuantity): array
    {
        $em = ManagerUtils::getRequiredManager($this->doctrine, PriceListRuleInterface::class);
        $repo = $em->getRepository(PriceListRuleInterface::class);

        if (!$repo instanceof PriceListRuleRepositoryInterface) {
            throw new InvalidArgumentException('The price manager requires the repository of price list rule extending the interface Klipper\Module\ProductBundle\Repository\PriceListRuleRepositoryInterface');
        }

        /* @var PriceListRuleInterface[] $rules */
        return $repo->createQueryBuilderForPriceManager('plr')
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
    }

    /**
     * @param int|string      $productId
     * @param null|int|string $productCombinationId
     * @param null|int|string $productRangeId
     * @param null|int|string $dependingOnProductId
     * @param null|int|string $dependingOnProductCombinationId
     * @param null|int|string $dependingOnProductRangeId
     */
    private function isValidRule(
        PriceListRuleInterface $rule,
        $productId,
        $productCombinationId,
        $productRangeId,
        $dependingOnProductId,
        $dependingOnProductCombinationId,
        $dependingOnProductRangeId
    ): bool {
        switch ($rule->getAppliedOn()) {
            case 'product_combination':
                if (null === $productCombinationId) {
                    return false;
                }

                return null !== $rule->getProductCombination()
                    && $productCombinationId === $rule->getProductCombination()->getId()
                    && $this->isValidRuleDependingOn($rule, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId)
                ;

            case 'product':
                return null !== $rule->getProduct()
                    && $productId === $rule->getProduct()->getId()
                    && $this->isValidRuleDependingOn($rule, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId)
                ;

            case 'product_range':
                return null !== $productRangeId
                    && null !== $rule->getProductRange() && $productRangeId === $rule->getProductRange()->getId()
                    && $this->isValidRuleDependingOn($rule, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId)
                ;

            case 'all_products':
                return $this->isValidRuleDependingOn($rule, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId);

            default:
                return false;
        }
    }

    private function isValidRuleDependingOn(PriceListRuleInterface $rule, $dependingOnProductId, $dependingOnProductCombinationId, $dependingOnProductRangeId): bool
    {
        switch ($rule->getDependingOn()) {
            case 'product_combination':
                if (null === $dependingOnProductCombinationId) {
                    return false;
                }

                return null !== $rule->getDependingOnProductCombination()
                    && $dependingOnProductCombinationId === $rule->getDependingOnProductCombination()->getId()
                ;

            case 'product':
                if (null === $dependingOnProductId) {
                    return false;
                }

                return null !== $rule->getDependingOnProduct()
                    && $dependingOnProductId === $rule->getDependingOnProduct()->getId()
                ;

            case 'product_range':
                if (null === $dependingOnProductRangeId) {
                    return false;
                }

                return null !== $rule->getDependingOnProductRange()
                    && $dependingOnProductRangeId === $rule->getDependingOnProductRange()->getId()
                ;

            case 'no_other_product':
                return true;

            default:
                return false;
        }
    }

    private function getPriceFromRule(
        PriceListRuleInterface $rule,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0,
        ?ProductInterface $dependingOnProduct = null,
        ?ProductCombinationInterface $dependingOnProductCombination = null,
        ?ProductRangeInterface $dependingOnProductRange = null
    ): Price {
        $extra = static::isExtraPrice($rule, $product);

        switch ($rule->getPriceCalculation()) {
            case 'price':
                return new Price((float) $rule->getValue(), $extra);

            case 'percent':
                $productPrice = (float) $product->getPrice();
                $rate = (float) $rule->getValue();

                return new Price($productPrice * $rate, $extra);

            case 'formula':
                return $this->getPriceFromRuleFormula(
                    $rule,
                    $product,
                    $productCombination,
                    $quantity,
                    $dependingOnProduct,
                    $dependingOnProductCombination,
                    $dependingOnProductRange
                );

            default:
                return new Price(0.0, $extra);
        }
    }

    private function getPriceFromRuleFormula(
        PriceListRuleInterface $rule,
        ProductInterface $product,
        ?ProductCombinationInterface $productCombination = null,
        int $quantity = 0,
        ?ProductInterface $dependingOnProduct = null,
        ?ProductCombinationInterface $dependingOnProductCombination = null,
        ?ProductRangeInterface $dependingOnProductRange = null
    ): Price {
        switch ($rule->getFormulaBasedOn()) {
            case 'selling_price':
                $price = null !== $productCombination
                    ? (float) $productCombination->getPrice() : (float) $product->getPrice();

                break;

            case 'other_price_list':
                $price = null !== $rule->getFormulaPriceList()
                    ? $this->getProductPrice(
                        $product,
                        $productCombination,
                        $rule->getFormulaPriceList(),
                        $quantity,
                        $dependingOnProduct,
                        $dependingOnProductCombination,
                        $dependingOnProductRange
                    )
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

        $finalPrice = $roundedMethod > 0
            ? PriceUtil::round($newPrice, $roundedMethod)
            : $newPrice;

        return new Price($finalPrice, static::isExtraPrice($rule, $product));
    }
}
