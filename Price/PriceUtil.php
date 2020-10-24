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
abstract class PriceUtil
{
    public static function round(float $price, float $roundedMethod): float
    {
        return round($price / $roundedMethod) * $roundedMethod;
    }
}
