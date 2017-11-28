<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\BaseTransaction;

trait PaymentReferenceField
{
    /**
     * @var string|null Payment reference of the transaction.
     */
    private $paymentReference;

    public function getPaymentReference(): string
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(?string $paymentReference): BaseTransaction
    {
        $this->paymentReference = $paymentReference;

        return $this;
    }
}
