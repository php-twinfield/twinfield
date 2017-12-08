<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait PaymentReferenceField
{
    /**
     * @var string|null Payment reference of the transaction.
     */
    private $paymentReference;

    /**
     * @return string|null
     */
    public function getPaymentReference(): ?string
    {
        return $this->paymentReference;
    }

    /**
     * @param string|null $paymentReference
     * @return $this
     */
    public function setPaymentReference(?string $paymentReference): self
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }
}
