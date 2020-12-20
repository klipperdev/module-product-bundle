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
use Klipper\Module\ProductBundle\Choice\ProductListRuleDependingOn;

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
            // Select global
            ->addSelect('pl')
            // Select for applied On
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
            ->addSelect($this->getCaseSelect(
                $alias,
                'int_type',
                'appliedOn',
                ProductListRuleAppliedOn::getValues()
            ))
            // Select for depending On
            ->addSelect('dopr')
            ->addSelect('dop')
            ->addSelect('dopc')
            ->addSelect('dopcai')
            ->addSelect('dopb')
            ->addSelect('doppt')
            ->addSelect('doppr')
            ->addSelect('dopdpc')
            ->addSelect('dopcaia')
            ->addSelect($this->getCaseSelect(
                $alias,
                'int_depending_type',
                'dependingOn',
                ProductListRuleDependingOn::getValues()
            ))
            // Left join for global
            ->leftJoin($alias.'.priceList', 'pl')
            // Left join for applied on
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
            // Left join for depending on
            ->leftJoin($alias.'.dependingOnProductRange', 'dopr')
            ->leftJoin($alias.'.dependingOnProduct', 'dop')
            ->leftJoin($alias.'.dependingOnProductCombination', 'dopc')
            ->leftJoin('dopc.attributeItems', 'dopcai')
            ->leftJoin('dop.brand', 'dopb')
            ->leftJoin('dop.productType', 'doppt')
            ->leftJoin('dop.productRange', 'doppr')
            ->leftJoin('dop.defaultProductCombination', 'dopdpc')
            ->leftJoin('dopcai.attribute', 'dopcaia')
            // Order by for applied on
            ->orderBy('int_type', 'desc')
            // Order by for depending on
            ->addOrderBy('int_depending_type', 'desc')
        ;
    }

    /**
     * @throws
     */
    private function getCaseSelect(string $alias, string $hiddenName, string $field, array $values): string
    {
        $platform = $this->getEntityManager()->getConnection()->getDatabasePlatform();
        $select = 'CASE';

        foreach ($values as $i => $type) {
            $select .= ' WHEN '.$alias.'.'.$field.' = '.$platform->quoteStringLiteral($type).' THEN '.$i;
        }

        return $select.' ELSE '.\count($values).' END AS HIDDEN '.$hiddenName;
    }
}
