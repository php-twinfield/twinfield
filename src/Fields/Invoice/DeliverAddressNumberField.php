<?php

namespace PhpTwinfield\Fields\Invoice;

trait DeliverAddressNumberField
{
    /**
     * Deliver address number field
     * Used by: Invoice
     *
     * @var int|null
     */
    private $deliverAddressNumber;

    /**
     * @return null|int
     */
    public function getDeliverAddressNumber(): ?int
    {
        return $this->deliverAddressNumber;
    }

    /**
     * @param null|int $deliverAddressNumber
     * @return $this
     */
    public function setDeliverAddressNumber(?int $deliverAddressNumber): self
    {
        $this->deliverAddressNumber = $deliverAddressNumber;
        return $this;
    }
}