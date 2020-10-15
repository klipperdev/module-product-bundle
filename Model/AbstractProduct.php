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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\DoctrineChoice\Validator\Constraints\EntityDoctrineChoice;
use Klipper\Component\Model\Traits\CurrencyableTrait;
use Klipper\Component\Model\Traits\NameableTrait;
use Klipper\Component\Model\Traits\OrganizationalRequiredTrait;
use Klipper\Component\Model\Traits\OwnerableTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Klipper\Component\Model\Traits\UserTrackableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractProduct implements ProductInterface
{
    use CurrencyableTrait;
    use NameableTrait;
    use OrganizationalRequiredTrait;
    use OwnerableTrait;
    use TimestampableTrait;
    use UserTrackableTrait;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="128")
     *
     * @Serializer\Expose
     */
    protected ?string $code = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="128")
     *
     * @Serializer\Expose
     */
    protected ?string $partNumber = null;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="7")
     * @Assert\Regex(pattern="/^#[0-9a-f]{6}$/i")
     *
     * @Serializer\Expose
     */
    protected ?string $color = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     *
     * @Serializer\Expose
     */
    protected bool $canBeSell = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     *
     * @Serializer\Expose
     */
    protected bool $canBeBuy = false;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="128")
     *
     * @Serializer\Expose
     */
    protected ?string $internalReference = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="128")
     *
     * @Serializer\Expose
     */
    protected ?string $barCode = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="128")
     *
     * @Serializer\Expose
     */
    protected ?string $description = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     *
     * @Serializer\Expose
     */
    protected ?float $price = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\BrandInterface")
     *
     * @Serializer\Expose
     */
    protected ?BrandInterface $brand = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Component\DoctrineChoice\Model\ChoiceInterface")
     *
     * @EntityDoctrineChoice("product_type")
     *
     * @Serializer\Expose
     */
    protected ?ChoiceInterface $productType = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductRangeInterface")
     *
     * @Serializer\Expose
     */
    protected ?ProductRangeInterface $productRange = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getPartNumber(): ?string
    {
        return $this->partNumber;
    }

    public function setPartNumber(?string $partNumber): self
    {
        $this->partNumber = $partNumber;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isCanBeSell(): bool
    {
        return $this->canBeSell;
    }

    public function setCanBeSell(bool $canBeSell): self
    {
        $this->canBeSell = $canBeSell;

        return $this;
    }

    public function isCanBeBuy(): bool
    {
        return $this->canBeBuy;
    }

    public function setCanBeBuy(bool $canBeBuy): self
    {
        $this->canBeBuy = $canBeBuy;

        return $this;
    }

    public function getInternalReference(): ?string
    {
        return $this->internalReference;
    }

    public function setInternalReference(?string $internalReference): self
    {
        $this->internalReference = $internalReference;

        return $this;
    }

    public function getBarCode(): ?string
    {
        return $this->barCode;
    }

    public function setBarCode(?string $barCode): self
    {
        $this->barCode = $barCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?BrandInterface
    {
        return $this->brand;
    }

    public function setBrand(?BrandInterface $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getProductType(): ?ChoiceInterface
    {
        return $this->productType;
    }

    public function setProductType(?ChoiceInterface $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    public function getProductRange(): ?ProductRangeInterface
    {
        return $this->productRange;
    }

    public function setProductRange(?ProductRangeInterface $productRange): self
    {
        $this->productRange = $productRange;

        return $this;
    }
}
