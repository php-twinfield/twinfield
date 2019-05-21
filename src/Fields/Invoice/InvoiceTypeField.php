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

    public function getInvoiceTypeToString(): ?string
    {
        if ($this->getInvoiceType() != null) {
            return $this->invoiceType->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setInvoiceType(?InvoiceType $invoiceType): self
    {
        $this->invoiceType = $invoiceType;
        return $this;
    }

    /**
     * @param string|null $invoiceTypeString
     * @return $this
     * @throws Exception
     */
    public function setInvoiceTypeFromString(?string $invoiceTypeString)
    {
        $invoiceType = new InvoiceType();
        $invoiceType->setCode($invoiceTypeString);
        return $this->setInvoiceType($invoiceType);
    }
}
