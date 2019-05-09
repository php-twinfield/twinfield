<?php

namespace PhpTwinfield\Fields\Invoice;

trait QuantityField
{
    /**
     * Quantity field
     * Used by: InvoiceLine
     *
     * @var int|null
     */
    private $quantity;

    /**
     * @return null|int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param null|int $quantity
     * @return $this
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}