<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\BaseTransaction;

trait InvoiceNumberField
{
    /**
     * @var string|null The invoice number.
     */
    private $invoiceNumber;

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): BaseTransaction
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
