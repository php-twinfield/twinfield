<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait Field1Field
{
    /**
     * Field 1 field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $field1;

    /**
     * @return null|string
     */
    public function getField1(): ?string
    {
        return $this->field1;
    }

    /**
     * @param null|string $field1
     * @return $this
     */
    public function setField1(?string $field1): self
    {
        $this->field1 = $field1;
        return $this;
    }
}