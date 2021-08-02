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

use Klipper\Module\ProductBundle\Exception\ProductCombinationAlreadyExistingReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationAttributeNotFoundException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationEmptyReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationInvalidProductReferenceException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationNotPersistException;
use Klipper\Module\ProductBundle\Exception\ProductCombinationProductNotFoundException;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductManagerInterface
{
    /**
     * Create the product combination from the reference.
     *
     * @throws ProductCombinationEmptyReferenceException
     * @throws ProductCombinationAlreadyExistingReferenceException
     * @throws ProductCombinationInvalidProductReferenceException
     * @throws ProductCombinationProductNotFoundException
     * @throws ProductCombinationAttributeNotFoundException
     * @throws ProductCombinationNotPersistException
     */
    public function createProductCombinationFromReference(string $reference, ?ProductInterface $product = null, ?string $separator = null): ProductCombinationInterface;
}
