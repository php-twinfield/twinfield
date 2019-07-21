<?php

namespace PhpTwinfield\Fields\Invoice;

trait InvoiceAddressNumberField
{
    /**
     * Invoice address number field
     * Used by: Invoice
     *
     * @var int|null
     */
    private $invoiceAddressNumber;

    /**
     * @return null|int
     */
    public function getInvoiceAddressNumber(): ?int
    {
        return $this->invoiceAddressNumber;
    }

    /**
     * @param null|int $invoiceAddressNumber
     * @return $this
     */
    public function setInvoiceAddressNumber(?int $invoiceAddressNumber): self
    {
        $this->invoiceAddressNumber = $invoiceAddressNumber;
        return $this;
    }
}
