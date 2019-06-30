<?php

namespace PhpTwinfield\Fields\Invoice;

/**
 * Invoice date field
 * Used by: Invoice
 *
 * @package PhpTwinfield\Traits
 */
trait InvoiceDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $invoiceDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoiceDate;
    }

    /**
     * @param \DateTimeInterface|null $invoiceDate
     * @return $this
     */
    public function setInvoiceDate(?\DateTimeInterface $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }
}
