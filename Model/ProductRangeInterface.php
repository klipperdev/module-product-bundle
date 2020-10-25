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
use Klipper\Component\Model\Traits\LabelableInterface;
use Klipper\Component\Model\Traits\NameableInterface;
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\OwnerableInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Component\Model\Traits\UserTrackableInterface;

/**
 * Product range interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductRangeInterface extends
    IdInterface,
    LabelableInterface,
    NameableInterface,
    OrganizationalRequiredInterface,
    OwnerableInterface,
    TimestampableInterface,
    UserTrackableInterface
{
}
