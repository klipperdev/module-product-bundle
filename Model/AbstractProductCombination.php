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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Model\Traits\OrganizationalRequiredTrait;
use Klipper\Component\Model\Traits\SortableTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Klipper\Component\Model\Traits\UserTrackableTrait;
use Klipper\Module\ProductBundle\Model\Traits\ProductableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product combination model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Serializer\ExclusionPolicy("all")
 */
abstract class AbstractProductCombination implements ProductCombinationInterface
{
    use OrganizationalRequiredTrait;
    use ProductableTrait;
    use SortableTrait;
    use TimestampableTrait;
    use UserTrackableTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Module\ProductBundle\Model\ProductInterface"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Assert\NotBlank
     *
     * @Gedmo\SortableGroup
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?ProductInterface $product = null;

    /**
     * @var null|AttributeItemInterface[]|Collection
     *
     * @ORM\ManyToMany(
     *     targetEntity="Klipper\Module\ProductBundle\Model\AttributeItemInterface",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(
     *     joinColumns={
     *         @ORM\JoinColumn(onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(onDelete="CASCADE", name="attribute_item_id")
     *     }
     * )
     *
     * @Assert\Count(min=1)
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(2)
     */
    protected ?Collection $attributeItems = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=128)
     *
     * @Serializer\Expose
     */
    protected ?string $reference = null;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=13)
     *
     * @Serializer\Expose
     */
    protected ?string $codeEan13 = null;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=12)
     *
     * @Serializer\Expose
     */
    protected ?string $codeUpc = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     *
     * @Serializer\Expose
     */
    protected ?float $price = null;

    public function getAttributeItems(): Collection
    {
        return $this->attributeItems ?: $this->attributeItems = new ArrayCollection();
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
