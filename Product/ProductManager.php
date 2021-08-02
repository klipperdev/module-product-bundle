<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Product;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Klipper\Component\Resource\Object\ObjectFactoryInterface;
use Klipper\Module\ProductBundle\Exception\ProductCombinationAlreadyExistingReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationAttributeNotFoundException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationEmptyReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationInvalidProductReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationNotPersistException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationProductNotFoundException;
use Klipper\Module\ProductBundle\Model\AttributeItemInterface;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ProductManager implements ProductManagerInterface
{
    private EntityManagerInterface $em;

    private ObjectFactoryInterface $objectFactory;

    private TranslatorInterface $translator;

    private string $separator;

    public function __construct(
        EntityManagerInterface $em,
        ObjectFactoryInterface $objectFactory,
        TranslatorInterface $translator,
        string $separator = '-'
    ) {
        $this->em = $em;
        $this->objectFactory = $objectFactory;
        $this->translator = $translator;
        $this->separator = $separator;
    }

    public function createProductCombinationFromReference(string $reference, ?ProductInterface $product = null, ?string $separator = null): ProductCombinationInterface
    {
        $separator = $this->getSeparator($separator);
        $parts = explode($separator, $reference);

        if (\count($parts) <= 1) {
            throw new ProductCombinationEmptyReferenceException(
                $this->translator->trans('klipper_product.combination_creator.empty_reference', [], 'validators')
            );
        }

        // Check if reference already exists
        try {
            $countProductCombination = $this->em->createQueryBuilder()
                ->select('count(pc)')
                ->from(ProductCombinationInterface::class, 'pc')
                ->where('pc.reference = :reference')
                ->setParameter('reference', $reference)
                ->getQuery()
                ->getSingleScalarResult()
            ;
        } catch (\Throwable $e) {
            $countProductCombination = 1;
        }

        if ($countProductCombination > 0) {
            throw new ProductCombinationAlreadyExistingReferenceException(
                $this->translator->trans('klipper_product.combination_creator.already_existing_reference', [], 'validators')
            );
        }

        // Check the product reference
        $productReference = array_shift($parts);

        if (null !== $product && $productReference !== $product->getReference()) {
            throw new ProductCombinationInvalidProductReferenceException(
                $this->translator->trans('klipper_product.combination_creator.invalid_product_reference', [], 'validators')
            );
        }

        try {
            $product = $this->em->createQueryBuilder()
                ->select('p')
                ->from(ProductInterface::class, 'p')
                ->where('p.reference = :reference')
                ->setParameter('reference', $productReference)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        } catch (NonUniqueResultException $e) {
            $product = null;
        }

        if (null === $product) {
            throw new ProductCombinationProductNotFoundException(
                $this->translator->trans('klipper_product.combination_creator.product_not_found', [], 'validators')
            );
        }

        // Create the product combination
        /** @var ProductCombinationInterface $productCombination */
        $productCombination = $this->objectFactory->create(ProductCombinationInterface::class);

        /** @var AttributeItemInterface[] $attributeItemsMap */
        $attributeItemsMap = [];
        /** @var AttributeItemInterface[] $attributeItems */
        $attributeItems = $this->em->getRepository(AttributeItemInterface::class)->findBy([
            'reference' => $parts,
        ]);

        foreach ($attributeItems as $attributeItem) {
            $attributeItemsMap[$attributeItem->getReference()] = $attributeItem;
        }

        $productCombination->setReference($reference);
        $productCombination->setProduct($product);

        foreach ($parts as $part) {
            if (isset($attributeItemsMap[$part])) {
                $productCombination->getAttributeItems()->add($attributeItemsMap[$part]);
            } else {
                throw new ProductCombinationAttributeNotFoundException(
                    $this->translator->trans('klipper_product.combination_creator.attribute_not_found', [
                        '{attribute}' => $part,
                    ], 'validators')
                );
            }
        }

        try {
            $this->em->persist($productCombination);
            $this->em->flush();
        } catch (\Throwable $e) {
            throw new ProductCombinationNotPersistException(
                $this->translator->trans('domain.database_error', [], 'KlipperResource'),
                0,
                $e
            );
        }

        return $productCombination;
    }

    private function getSeparator(?string $separator = null): string
    {
        return !empty($separator) ? $separator : $this->separator;
    }
}
