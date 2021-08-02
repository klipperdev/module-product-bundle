<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Representation;

use Klipper\Module\ProductBundle\Model\ProductInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ProductCombinationCreator
{
    /**
     * @Assert\NotBlank
     */
    private ?string $reference = null;

    private ?ProductInterface $product = null;

    private ?string $separator = null;

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setProduct(?ProductInterface $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setSeparator(?string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->separator;
    }
}
