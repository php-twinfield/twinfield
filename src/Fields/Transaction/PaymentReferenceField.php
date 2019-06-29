<?php

namespace PhpTwinfield\Fields\Transaction;

trait PaymentReferenceField
{
    /**
     * Payment reference field
     * Used by: PurchaseTransaction, SalesTransaction
     *
     * @var string|null
     */
    private $paymentReference;

    /**
     * @return null|string
     */
    public function getPaymentReference(): ?string
    {
        return $this->paymentReference;
    }

    /**
     * @param null|string $paymentReference
     * @return $this
     */
    public function setPaymentReference(?string $paymentReference): self
    {
        $this->paymentReference = $paymentReference;
        return $this;
    }
}
