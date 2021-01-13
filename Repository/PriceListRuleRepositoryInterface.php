<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PriceListRuleRepositoryInterface extends ObjectRepository
{
    public function createQueryBuilderForPriceManager($alias, $indexBy = null): QueryBuilder;
}
