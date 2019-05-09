<?php

namespace PhpTwinfield\Fields\Invoice;

trait InvoiceNumberField
{
    /**
     * Invoice number field
     * Used by: Invoice
     *
     * @var int|null
     */
    private $invoiceNumber;

    /**
     * @return null|int
     */
    public function getInvoiceNumber(): ?int
    {
        return $this->invoiceNumber;
    }

    /**
     * @param null|int $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber(?int $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }
}