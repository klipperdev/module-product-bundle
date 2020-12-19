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

use Klipper\Module\ProductBundle\Model\PriceListInterface;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PriceManagerInterface
{
    /**
     * @param int|ProductInterface|string                 $product                       The instance or id of the product
     * @param null|int|ProductCombinationInterface|string $productCombination            The instance or id of the product combination
     * @param null|int|PriceListInterface|string          $priceList                     The instance or id of the price list
     * @param int                                         $quantity                      The quantity to select the good price list rule
     * @param null|int|ProductInterface|string            $dependingOnProduct            The instance or id of the depending on product
     * @param null|int|ProductCombinationInterface|string $dependingOnProductCombination The instance or id of the depending on product combination
     * @param null|int|ProductCombinationInterface|string $dependingOnProductRange       The instance or id of the depending on product range
     */
    public function getProductPrice(
        $product,
        $productCombination = null,
        $priceList = null,
        int $quantity = 0,
        $dependingOnProduct = null,
        $dependingOnProductCombination = null,
        $dependingOnProductRange = null
    ): float;
}
