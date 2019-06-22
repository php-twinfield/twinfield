<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;

trait InvoiceAmountField
{
    /**
     * Invoice amount field
     * Used by: Invoice
     *
     * @var Money|null
     */
    private $invoiceAmount;

    /**
     * @return Money|null
     */
    public function getInvoiceAmount(): ?Money
    {
        return $this->invoiceAmount;
    }

    /**
     * @param Money|null $invoiceAmount
     * @return $this
     */
    public function setInvoiceAmount(?Money $invoiceAmount)
    {
        $this->invoiceAmount = $invoiceAmount;

        return $this;
    }
}