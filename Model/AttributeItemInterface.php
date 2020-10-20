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

use Klipper\Component\Model\Traits\LabelableInterface;
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\SortableInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Contracts\Model\IdInterface;

/**
 * Attribute item interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AttributeItemInterface extends
    IdInterface,
    LabelableInterface,
    OrganizationalRequiredInterface,
    SortableInterface,
    TimestampableInterface
{
    /**
     * @return static
     */
    public function setAttribute(?AttributeInterface $attribute);

    public function getAttribute(): ?AttributeInterface;

    /**
     * @return static
     */
    public function setColor(?string $color);

    public function getColor(): ?string;
}
