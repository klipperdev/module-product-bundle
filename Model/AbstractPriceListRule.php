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
use Klipper\Component\Model\Traits\OrganizationalRequiredTrait;
use Klipper\Component\Model\Traits\TimestampableTrait;
use Klipper\Module\ProductBundle\Validator\Constraints as KlipperProductAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Price list rule model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Serializer\ExclusionPolicy("all")
 */
abstract class AbstractPriceListRule implements PriceListRuleInterface
{
    use OrganizationalRequiredTrait;
    use TimestampableTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\PriceListInterface", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?PriceListInterface $priceList = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @KlipperProductAssert\ProductListRuleAppliedOnChoice
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=128)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $appliedOn = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductRangeInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['product_range'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['all_products'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?ProductRangeInterface $productRange = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['product', 'product_combination'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['all_products'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?ProductInterface $product = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductCombinationInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['product_combination'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getAppliedOn() in ['all_products'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(3)
     */
    protected ?ProductCombinationInterface $productCombination = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @KlipperProductAssert\ProductListRuleDependingOnChoice
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=128)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $dependingOn = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductRangeInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['product_range'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['no_other_product'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?ProductRangeInterface $dependingOnProductRange = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['product', 'product_combination'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['no_other_product'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?ProductInterface $dependingOnProduct = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\ProductCombinationInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['product_combination'] && !value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getDependingOn() in ['no_other_product'] && value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(3)
     */
    protected ?ProductCombinationInterface $dependingOnProductCombination = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     *
     * @Serializer\Expose
     */
    protected ?float $minimumQuantity = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     *
     * @Serializer\Expose
     */
    protected ?\DateTimeInterface $startAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     *
     * @Serializer\Expose
     */
    protected ?\DateTimeInterface $endAt = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @KlipperProductAssert\ProductListRulePriceCalculationChoice()
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=128)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?string $priceCalculation = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['flat_rate', 'percent'] && null === value)",
     *     message="This value should not be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $value = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     *
     * @KlipperProductAssert\ProductListRuleBasedOnChoice()
     * @Assert\Type(type="string")
     * @Assert\Length(min=0, max=128)
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?string $formulaBasedOn = null;

    /**
     * @ORM\ManyToOne(targetEntity="Klipper\Module\ProductBundle\Model\PriceListInterface", fetch="EAGER")
     *
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && this.getFormulaBasedOn() in ['other_price_list'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    protected ?PriceListInterface $formulaPriceList = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $formulaPriceReduction = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $formulaRoundedMethod = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $formulaMargin = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $formulaMinimumMargin = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="float")
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() not in ['formula'] && null !== value)",
     *     message="This value should be blank."
     * )
     * @Assert\Expression(
     *     expression="!(this.getPriceCalculation() in ['formula'] && null === value)",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?float $formulaMaximumMargin = null;

    public function getPriceList(): ?PriceListInterface
    {
        return $this->priceList;
    }

    public function setPriceList(?PriceListInterface $priceList): self
    {
        $this->priceList = $priceList;

        return $this;
    }

    public function getAppliedOn(): ?string
    {
        return $this->appliedOn;
    }

    public function setAppliedOn(?string $appliedOn): self
    {
        $this->appliedOn = $appliedOn;

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

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProductCombination(): ?ProductCombinationInterface
    {
        return $this->productCombination;
    }

    public function setProductCombination(?ProductCombinationInterface $productCombination): self
    {
        $this->productCombination = $productCombination;

        return $this;
    }

    public function getDependingOn(): ?string
    {
        return $this->dependingOn;
    }

    public function setDependingOn(?string $dependingOn): self
    {
        $this->dependingOn = $dependingOn;

        return $this;
    }

    public function getDependingOnProductRange(): ?ProductRangeInterface
    {
        return $this->dependingOnProductRange;
    }

    public function setDependingOnProductRange(?ProductRangeInterface $productRange): self
    {
        $this->dependingOnProductRange = $productRange;

        return $this;
    }

    public function getDependingOnProduct(): ?ProductInterface
    {
        return $this->dependingOnProduct;
    }

    public function setDependingOnProduct(?ProductInterface $product): self
    {
        $this->dependingOnProduct = $product;

        return $this;
    }

    public function getDependingOnProductCombination(): ?ProductCombinationInterface
    {
        return $this->dependingOnProductCombination;
    }

    public function setDependingOnProductCombination(?ProductCombinationInterface $productCombination): self
    {
        $this->dependingOnProductCombination = $productCombination;

        return $this;
    }

    public function getMinimumQuantity(): ?float
    {
        return $this->minimumQuantity;
    }

    public function setMinimumQuantity(?float $minimumQuantity): self
    {
        $this->minimumQuantity = $minimumQuantity;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getPriceCalculation(): ?string
    {
        return $this->priceCalculation;
    }

    public function setPriceCalculation(?string $priceCalculation): self
    {
        $this->priceCalculation = $priceCalculation;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getFormulaBasedOn(): ?string
    {
        return $this->formulaBasedOn;
    }

    public function setFormulaBasedOn(?string $formulaBasedOn): self
    {
        $this->formulaBasedOn = $formulaBasedOn;

        return $this;
    }

    public function getFormulaPriceList(): ?PriceListInterface
    {
        return $this->formulaPriceList;
    }

    public function setFormulaPriceList(?PriceListInterface $formulaPriceList): self
    {
        $this->formulaPriceList = $formulaPriceList;

        return $this;
    }

    public function getFormulaPriceReduction(): ?float
    {
        return $this->formulaPriceReduction;
    }

    public function setFormulaPriceReduction(?float $formulaPriceReduction): self
    {
        $this->formulaPriceReduction = $formulaPriceReduction;

        return $this;
    }

    public function getFormulaRoundedMethod(): ?float
    {
        return $this->formulaRoundedMethod;
    }

    public function setFormulaRoundedMethod(?float $formulaRoundedMethod): self
    {
        $this->formulaRoundedMethod = $formulaRoundedMethod;

        return $this;
    }

    public function getFormulaMargin(): ?float
    {
        return $this->formulaMargin;
    }

    public function setFormulaMargin(?float $formulaMargin): self
    {
        $this->formulaMargin = $formulaMargin;

        return $this;
    }

    public function getFormulaMinimumMargin(): ?float
    {
        return $this->formulaMinimumMargin;
    }

    public function setFormulaMinimumMargin(?float $formulaMinimumMargin): self
    {
        $this->formulaMinimumMargin = $formulaMinimumMargin;

        return $this;
    }

    public function getFormulaMaximumMargin(): ?float
    {
        return $this->formulaMaximumMargin;
    }

    public function setFormulaMaximumMargin(?float $formulaMaximumMargin): self
    {
        $this->formulaMaximumMargin = $formulaMaximumMargin;

        return $this;
    }
}
