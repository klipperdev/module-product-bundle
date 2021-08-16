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
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Model\Traits\LabelableTrait;
use Klipper\Component\Model\Traits\OrganizationalRequiredTrait;
use Klipper\Component\Model\Traits\SortableTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attribute item model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Serializer\ExclusionPolicy("all")
 */
abstract class AbstractAttributeItem implements AttributeItemInterface
{
    use LabelableTrait;
    use OrganizationalRequiredTrait;
    use SortableTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Module\ProductBundle\Model\AttributeInterface"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     *
     * @Gedmo\SortableGroup
     *
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?AttributeInterface $attribute = null;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=9)
     * @Assert\Regex(pattern="/^#[0-9a-f]{6,8}$/i")
     *
     * @Serializer\Expose
     */
    protected ?string $color = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=255)
     *
     * @Serializer\Expose
     */
    protected ?string $reference = null;

    public function setAttribute(?AttributeInterface $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttribute(): ?AttributeInterface
    {
        return $this->attribute;
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

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
