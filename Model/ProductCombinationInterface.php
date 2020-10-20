<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Model;

use Doctrine\Common\Collections\Collection;
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Component\Model\Traits\UserTrackableInterface;
use Klipper\Contracts\Model\IdInterface;
use Klipper\Module\ProductBundle\Model\Traits\ProductableRequiredInterface;

/**
 * Product combination interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductCombinationInterface extends
    IdInterface,
    OrganizationalRequiredInterface,
    ProductableRequiredInterface,
    TimestampableInterface,
    UserTrackableInterface
{
    /**
     * @return AttributeItemInterface[]|Collection
     */
    public function getAttributeItems(): Collection;

    /**
     * @return static
     */
    public function setReference(?string $reference);

    public function getReference(): ?string;

    /**
     * @return static
     */
    public function setCodeEan13(?string $codeEan13);

    public function getCodeEan13(): ?string;

    /**
     * @return static
     */
    public function setCodeUpc(?string $codeUpc);

    public function getCodeUpc(): ?string;
}
