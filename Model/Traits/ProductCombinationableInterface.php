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

use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductCombinationableInterface
{
    public function getProductCombination(): ?ProductCombinationInterface;

    /**
     * @return static
     */
    public function setProductCombination(?ProductCombinationInterface $productCombination);

    /**
     * @return null|int|string
     */
    public function getProductCombinationId();
}
