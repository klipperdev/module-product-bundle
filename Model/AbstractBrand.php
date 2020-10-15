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

use Klipper\Component\Model\Traits\NameableTrait;
use Klipper\Component\Model\Traits\OrganizationalRequiredTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Klipper\Component\Model\Traits\UserTrackableTrait;

/**
 * Brand model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractBrand implements BrandInterface
{
    use NameableTrait;
    use OrganizationalRequiredTrait;
    use TimestampableTrait;
    use UserTrackableTrait;
}
