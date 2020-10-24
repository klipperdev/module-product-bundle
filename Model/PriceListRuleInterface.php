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

use Klipper\Component\Model\Traits\OrganizationalRequiredInterface;
use Klipper\Component\Model\Traits\TimestampableInterface;
use Klipper\Contracts\Model\IdInterface;

/**
 * Price list rule interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PriceListRuleInterface extends
    IdInterface,
    OrganizationalRequiredInterface,
    TimestampableInterface
{
    /**
     * @return static
     */
    public function setPriceList(?PriceListInterface $priceList);

    public function getPriceList(): ?PriceListInterface;

    /**
     * @return static
     */
    public function setAppliedOn(?string $appliedOn);

    public function getAppliedOn(): ?string;

    /**
     * @return static
     */
    public function setProductRange(?ProductRangeInterface $productRange);

    public function getProductRange(): ?ProductRangeInterface;

    /**
     * @return static
     */
    public function setProduct(?ProductInterface $product);

    public function getProduct(): ?ProductInterface;

    /**
     * @return static
     */
    public function setProductCombination(?ProductCombinationInterface $productCombination);

    public function getProductCombination(): ?ProductCombinationInterface;

    /**
     * @return static
     */
    public function setMinimumQuantity(?float $minimumQuantity);

    public function getMinimumQuantity(): ?float;

    /**
     * @return static
     */
    public function setStartAt(?\DateTimeInterface $startAt);

    public function getStartAt(): ?\DateTimeInterface;

    /**
     * @return static
     */
    public function setEndAt(?\DateTimeInterface $endAt);

    public function getEndAt(): ?\DateTimeInterface;

    /**
     * @return static
     */
    public function setPriceCalculation(?string $priceCalculation);

    public function getPriceCalculation(): ?string;

    /**
     * @return static
     */
    public function setValue(?float $value);

    public function getValue(): ?float;

    /**
     * @return static
     */
    public function setBasedOn(?string $basedOn);

    public function getBasedOn(): ?string;

    /**
     * @return static
     */
    public function setFormulaPriceList(?PriceListInterface $formulaPriceList);

    public function getFormulaPriceList(): ?PriceListInterface;

    /**
     * @return static
     */
    public function setFormulaPriceReduction(?float $formulaPriceReduction): self;

    public function getFormulaPriceReduction(): ?float;

    /**
     * @return static
     */
    public function setFormulaRoundedMethod(?float $formulaRoundedMethod);

    public function getFormulaRoundedMethod(): ?float;

    /**
     * @return static
     */
    public function setFormulaMargin(?float $formulaMargin);

    public function getFormulaMargin(): ?float;

    /**
     * @return static
     */
    public function setFormulaMinimumMargin(?float $formulaMinimumMargin);

    public function getFormulaMinimumMargin(): ?float;

    /**
     * @return static
     */
    public function setFormulaMaximumMargin(?float $formulaMaximumMargin);

    public function getFormulaMaximumMargin(): ?float;
}
