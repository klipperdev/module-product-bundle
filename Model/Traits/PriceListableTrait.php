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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Module\ProductBundle\Model\PriceListInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PriceListableTrait
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Module\ProductBundle\Model\PriceListInterface",
     *     fetch="EXTRA_LAZY"
     * )
     *
     * @Serializer\Type("Relation")
     * @Serializer\MaxDepth(depth=1)
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?PriceListInterface $priceList = null;

    public function getPriceList(): ?PriceListInterface
    {
        return $this->priceList;
    }

    public function setPriceList(?PriceListInterface $priceList): self
    {
        $this->priceList = $priceList;

        return $this;
    }

    public function getPriceListId()
    {
        return null !== $this->getPriceList()
            ? $this->getPriceList()->getId()
            : null;
    }
}
