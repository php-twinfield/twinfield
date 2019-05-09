<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Enums\InvoiceStatus;

trait InvoiceStatusField
{
    /**
     * Invoice status field
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

    /**
     * @param string|null $statusString
     * @return $this
     * @throws Exception
     */
    public function setStatusFromString(?string $statusString)
    {
        return $this->setStatus(new InvoiceStatus((string)$statusString));
    }
}