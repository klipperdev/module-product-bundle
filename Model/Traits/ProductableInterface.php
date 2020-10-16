<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Model\Traits;

use Klipper\Module\ProductBundle\Model\ProductInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductableInterface
{
    public function getProduct(): ?ProductInterface;

    /**
     * @return static
     */
    public function setProduct(?ProductInterface $product);

    /**
     * @return null|int|string
     */
    public function getProductId();
}
