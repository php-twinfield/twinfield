<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\InvoiceType;

/**
 * The invoice type
 * Used by: Invoice
 *
 * @package PhpTwinfield\Traits
 */
trait InvoiceTypeField
{
    /**
     * @var InvoiceType|null
     */
    private $invoiceType;

    public function getInvoiceType(): ?InvoiceType
    {
        return $this->invoiceType;
    }

    /**
     * @return $this
     */
    public function setInvoiceType(?InvoiceType $invoiceType): self
    {
        $this->invoiceType = $invoiceType;
        return $this;
    }
}
