<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Repository\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Klipper\Module\ProductBundle\Choice\ProductListRuleAppliedOn;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @method QueryBuilder           createQueryBuilder(string $alias, $indexBy = null)
 * @method ClassMetadata          getClassMetadata()
 * @method EntityManagerInterface getEntityManager()
 */
trait PriceListRuleRepositoryTrait
{
    public function createQueryBuilderForPriceManager($alias, $indexBy = null): QueryBuilder
    {
        return $this->createQueryBuilder($alias, $indexBy)
            ->addSelect('pl')
            ->addSelect('pr')
            ->addSelect('p')
            ->addSelect('pc')
            ->addSelect('fpl')
            ->addSelect('pcai')
            ->addSelect('pb')
            ->addSelect('ppt')
            ->addSelect('ppr')
            ->addSelect('pdpc')
            ->addSelect('pcaia')
            ->addSelect($this->getCaseSelect($alias, 'int_type'))
            ->leftJoin($alias.'.priceList', 'pl')
            ->leftJoin($alias.'.productRange', 'pr')
            ->leftJoin($alias.'.product', 'p')
            ->leftJoin($alias.'.productCombination', 'pc')
            ->leftJoin($alias.'.formulaPriceList', 'fpl')
            ->leftJoin('pc.attributeItems', 'pcai')
            ->leftJoin('p.brand', 'pb')
            ->leftJoin('p.productType', 'ppt')
            ->leftJoin('p.productRange', 'ppr')
            ->leftJoin('p.defaultProductCombination', 'pdpc')
            ->leftJoin('pcai.attribute', 'pcaia')
            ->orderBy('int_type', 'desc')
        ;
    }

    /**
     * @throws
     */
    private function getCaseSelect(string $alias, string $hiddenName): string
    {
        $platform = $this->getEntityManager()->getConnection()->getDatabasePlatform();
        $select = 'CASE';

        foreach (ProductListRuleAppliedOn::getValues() as $i => $type) {
            $select .= ' WHEN '.$alias.'.appliedOn = '.$platform->quoteStringLiteral($type).' THEN '.$i;
        }

        return $select.' ELSE '.\count(ProductListRuleAppliedOn::getValues()).' END AS HIDDEN '.$hiddenName;
    }
}
