<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Choice;

use Klipper\Component\Choice\ChoiceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
final class ProductListRulePriceCalculation implements ChoiceInterface
{
    public static function listIdentifiers(): array
    {
        return [
            'price' => 'product_list_rule_price_calculation.price',
            'percent' => 'product_list_rule_price_calculation.percent',
            'formula' => 'product_list_rule_price_calculation.formula',
        ];
    }

    public static function getValues(): array
    {
        return array_keys(static::listIdentifiers());
    }

    public static function getTranslationDomain(): string
    {
        return 'choices';
    }
}
