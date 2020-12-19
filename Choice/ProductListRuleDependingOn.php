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
final class ProductListRuleDependingOn implements ChoiceInterface
{
    public static function listIdentifiers(): array
    {
        return [
            'no_other_product' => 'product_list_rule_depending_on.no_other_product',
            'product_range' => 'product_list_rule_depending_on.product_range',
            'product' => 'product_list_rule_depending_on.product',
            'product_combination' => 'product_list_rule_depending_on.product_combination',
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
