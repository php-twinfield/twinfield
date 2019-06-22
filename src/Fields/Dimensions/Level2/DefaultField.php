<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait DefaultField
{
    /**
     * Default field
     * Used by: CustomerAddress, CustomerBank, SupplierAddress, SupplierBank
     *
     * @var bool
     */
    private $default;

    /**
     * @return bool
     */
    public function getDefault(): ?bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return $this
     */
    public function setDefault(?bool $default): self
    {
        $this->default = $default;
        return $this;
    }
}