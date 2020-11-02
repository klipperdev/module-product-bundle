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
use Klipper\Component\Model\Traits\OwnerableOptionalTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Klipper\Component\Model\Traits\UserTrackableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Serializer\ExclusionPolicy("all")
 */
abstract class AbstractProduct implements ProductInterface
{
    use CurrencyableTrait;
    use NameableTrait;
    use OrganizationalRequiredTrait;
    use OwnerableOptionalTrait;
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
    protected ?string $reference = null;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="13")
     *
     * @Serializer\Expose
     */
    protected ?string $codeEan13 = null;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="12")
     *
     * @Serializer\Expose
     */
    protected ?string $codeUpc = null;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min="0", max="9")
     * @Assert\Regex(pattern="/^#[0-9a-f]{6,8}$/i")
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
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\BrandInterface", fetch="EAGER")
     *
     * @Serializer\Expose
     */
    protected ?BrandInterface $brand = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Component\DoctrineChoice\Model\ChoiceInterface", fetch="EAGER")
     *
     * @EntityDoctrineChoice("product_type")
     *
     * @Serializer\Expose
     */
    protected ?ChoiceInterface $productType = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductRangeInterface", fetch="EAGER")
     *
     * @Serializer\Expose
     */
    protected ?ProductRangeInterface $productRange = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Module\ProductBundle\Model\ProductCombinationInterface",
     *     fetch="EAGER"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Serializer\Expose
     */
    protected ?ProductCombinationInterface $defaultProductCombination = null;

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setCodeEan13(?string $codeEan13): self
    {
        $this->codeEan13 = $codeEan13;

        return $this;
    }

    public function getCodeEan13(): ?string
    {
        return $this->codeEan13;
    }

    public function setCodeUpc(?string $codeUpc): self
    {
        $this->codeUpc = $codeUpc;

        return $this;
    }

    public function getCodeUpc(): ?string
    {
        return $this->codeUpc;
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

    public function getDefaultProductCombination(): ?ProductCombinationInterface
    {
        return $this->defaultProductCombination;
    }

    public function setDefaultProductCombination(?ProductCombinationInterface $defaultProductCombination): self
    {
        $this->defaultProductCombination = $defaultProductCombination;

        return $this;
    }
}
