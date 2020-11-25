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
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ProductCombinationableTrait
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Module\ProductBundle\Model\ProductCombinationInterface",
     *     fetch="EAGER"
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(3)
     */
    protected ?ProductCombinationInterface $productCombination = null;

    public function getProductCombination(): ?ProductCombinationInterface
    {
        return $this->productCombination;
    }

    public function setProductCombination(?ProductCombinationInterface $productCombination): self
    {
        $this->productCombination = $productCombination;

        return $this;
    }

    public function getProductCombinationId()
    {
        return null !== $this->getProductCombination()
            ? $this->getProductCombination()->getId()
            : null;
    }
}
