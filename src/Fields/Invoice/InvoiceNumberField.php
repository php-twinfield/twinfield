<?php

namespace PhpTwinfield\Fields\Invoice;

trait InvoiceNumberField
{
    /**
     * Invoice number field
     * Used by: Invoice
     *
     * @var string|null
     */
    private $invoiceNumber;

    /**
     * @return null|string
     */
    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    /**
     * @param null|string $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }
}