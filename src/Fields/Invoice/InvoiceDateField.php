<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Invoice date field
 * Used by: Invoice
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
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
     * @return string|null
     */
    public function getInvoiceDateToString(): ?string
    {
        if ($this->getInvoiceDate() != null) {
            return Util::formatDate($this->getInvoiceDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $invoiceDateString
     * @return $this
     * @throws Exception
     */
    public function setInvoiceDateFromString(?string $invoiceDateString)
    {
        if ((bool)strtotime($invoiceDateString)) {
            return $this->setInvoiceDate(Util::parseDate($invoiceDateString));
        } else {
            return $this->setInvoiceDate(null);
        }
    }
}