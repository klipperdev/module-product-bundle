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
use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\OwnerableInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Component\Model\Traits\UserTrackableInterface;
use Klipper\Contracts\Model\CurrencyableInterface;
use Klipper\Contracts\Model\IdInterface;

/**
 * Product interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ProductInterface extends
    IdInterface,
    CurrencyableInterface,
    OrganizationalRequiredInterface,
    OwnerableInterface,
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
    public function setCodeEan13(?string $codeEan13);

    public function getCodeEan13(): ?string;

    /**
     * @return static
     */
    public function setCodeUpc(?string $codeUpc);

    public function getCodeUpc(): ?string;

    /**
     * @return static
     */
    public function setColor(?string $color);

    public function getColor(): ?string;

    /**
     * @return static
     */
    public function setCanBeSell(bool $canBeSell);

    public function isCanBeSell(): bool;

    /**
     * @return static
     */
    public function setCanBeBuy(bool $canBeBuy);

    public function isCanBeBuy(): bool;

    /**
     * @return static
     */
    public function setDescription(?string $description);

    public function getDescription(): ?string;

    /**
     * @return static
     */
    public function setPrice(?float $price);

    public function getPrice(): ?float;

    /**
     * @return static
     */
    public function setBrand(?BrandInterface $brand);

    public function getBrand(): ?BrandInterface;

    /**
     * @return static
     */
    public function setProductType(?ChoiceInterface $productType);

    public function getProductType(): ?ChoiceInterface;

    /**
     * @return static
     */
    public function setProductRange(?ProductRangeInterface $productRange);

    public function getProductRange(): ?ProductRangeInterface;

    /**
     * @return static
     */
    public function setDefaultProductCombination(?ProductCombinationInterface $defaultProductCombination);

    public function getDefaultProductCombination(): ?ProductCombinationInterface;
}
