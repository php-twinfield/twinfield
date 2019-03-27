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
    public function getPaymentReference()
    {
        return $this->paymentReference;
    }

    /**
     * @param string|null $paymentReference
     * @return $this
     */
    public function setPaymentReference($paymentReference = null): self
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }
}
