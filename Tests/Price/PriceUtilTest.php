<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Tests\Price;

use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PriceUtilTest extends TestCase
{
    public function getRoundValues(): array
    {
        return [
            [100, 100.2, 0.5],
            [100.5, 100.3, 0.5],
            [100, 100.02, 0.05],
            [100.05, 100.03, 0.05],
            [155, 153.32, 5.0],
            [153, 153.32, 1.0],
            [154, 153.5, 1.0],
            [154, 153.51, 1.0],
        ];
    }

    /**
     * @dataProvider getRoundValues
     */
    public function testRound(float $expected, float $price, float $roundedMethod): void
    {
        static::assertSame($expected, PriceUtil::round($price, $roundedMethod));
    }
}
