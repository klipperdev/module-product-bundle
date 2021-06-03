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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
final class Price
{
    private float $price;

    private bool $extra;

    public function __construct(float $price, bool $extra = false)
    {
        $this->price = $price;
        $this->extra = $extra;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function isExtra(): bool
    {
        return $this->extra;
    }
}
