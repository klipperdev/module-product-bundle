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

use Klipper\Component\Model\Traits\IdInterface;
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Component\Model\Traits\UserTrackableInterface;
use Klipper\Module\PartnerBundle\Model\AccountInterface;
use Klipper\Module\ProductBundle\Model\Traits\ProductableRequiredInterface;

/**
 * Product supplier interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductSupplierInterface extends
    IdInterface,
    OrganizationalRequiredInterface,
    ProductableRequiredInterface,
    TimestampableInterface,
    UserTrackableInterface
{
    /**
     * @return static
     */
    public function setReference(?string $reference);

    public function getReference(): ?string;

    /**
     * @return static
     */
    public function setQuantity(?float $quantity);

    public function getQuantity(): ?float;

    /**
     * @return static
     */
    public function setSupplier(?AccountInterface $supplier);

    public function getSupplier(): ?AccountInterface;
}
