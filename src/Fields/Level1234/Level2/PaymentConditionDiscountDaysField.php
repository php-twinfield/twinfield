<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait PaymentConditionDiscountDaysField
{
    /**
     * Payment condition discount days field
     * Used by: Customer, Supplier
     *
     * @var int|null
     */
    private $paymentConditionDiscountDays;

    /**
     * @return null|int
     */
    public function getPaymentConditionDiscountDays(): ?int
    {
        return $this->paymentConditionDiscountDays;
    }

    /**
     * @param null|int $paymentConditionDiscountDays
     * @return $this
     */
    public function setPaymentConditionDiscountDays(?int $paymentConditionDiscountDays): self
    {
        $this->paymentConditionDiscountDays = $paymentConditionDiscountDays;
        return $this;
    }
}