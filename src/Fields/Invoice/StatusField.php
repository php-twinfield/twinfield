<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Enums\InvoiceStatus;

trait StatusField
{
    /**
     * Status field
     * Used by: Invoice
     *
     * @var InvoiceStatus|null
     */
    private $status;

    public function getStatus(): ?InvoiceStatus
    {
        return $this->status;
    }

    /**
     * @param InvoiceStatus|null $status
     * @return $this
     */
    public function setStatus(?InvoiceStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
}
