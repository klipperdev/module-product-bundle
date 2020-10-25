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

use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\Model\Traits\IdInterface;
use Klipper\Component\Model\Traits\LabelableInterface;
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\SortableInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;

/**
 * Attribute interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AttributeInterface extends
    IdInterface,
    LabelableInterface,
    OrganizationalRequiredInterface,
    SortableInterface,
    TimestampableInterface
{
    /**
     * @return static
     */
    public function setType(?ChoiceInterface $type);

    public function getType(): ?ChoiceInterface;
}
