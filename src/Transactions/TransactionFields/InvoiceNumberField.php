<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait InvoiceNumberField
{
    /**
     * @var string|null The invoice number.
     */
    private $invoiceNumber;

    /**
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string|null $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber = null)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
