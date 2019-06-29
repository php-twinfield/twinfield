<?php

namespace PhpTwinfield\Fields\Invoice;

trait QuantityField
{
    /**
     * Quantity field
     * Used by: InvoiceLine
     *
     * @var float|null
     */
    private $quantity;

    /**
     * @return null|float
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param null|float $quantity
     * @return $this
     */
    public function setQuantity(?float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
