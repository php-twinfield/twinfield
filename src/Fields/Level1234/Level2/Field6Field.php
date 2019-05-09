<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait Field6Field
{
    /**
     * Field 6 field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $field6;

    /**
     * @return null|string
     */
    public function getField6(): ?string
    {
        return $this->field6;
    }

    /**
     * @param null|string $field6
     * @return $this
     */
    public function setField6(?string $field6): self
    {
        $this->field6 = $field6;
        return $this;
    }
}