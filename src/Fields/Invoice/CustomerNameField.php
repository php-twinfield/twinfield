<?php

namespace PhpTwinfield\Fields\Invoice;

trait CustomerNameField
{
    /**
     * Customer name field
     * Used by: Invoice
     *
     * @var string|null
     */
    private $customerName;

    /**
     * @return null|string
     */
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     * @param null|string $customerName
     * @return $this
     */
    public function setCustomerName(?string $customerName): self
    {
        $this->customerName = $customerName;
        return $this;
    }
}
