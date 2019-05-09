<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait PaymentConditionDiscountPercentageField
{
    /**
     * Payment condition discount percentage field
     * Used by: Customer, Supplier
     *
     * @var float|null
     */
    private $paymentConditionDiscountPercentage;

    /**
     * @return null|float
     */
    public function getPaymentConditionDiscountPercentage(): ?float
    {
        return $this->paymentConditionDiscountPercentage;
    }

    /**
     * @param null|float $paymentConditionDiscountPercentage
     * @return $this
     */
    public function setPaymentConditionDiscountPercentage(?float $paymentConditionDiscountPercentage): self
    {
        $this->paymentConditionDiscountPercentage = $paymentConditionDiscountPercentage;
        return $this;
    }
}