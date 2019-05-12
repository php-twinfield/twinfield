<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait Field2Field
{
    /**
     * Field 2 field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $field2;

    /**
     * @return null|string
     */
    public function getField2(): ?string
    {
        return $this->field2;
    }

    /**
     * @param null|string $field2
     * @return $this
     */
    public function setField2(?string $field2): self
    {
        $this->field2 = $field2;
        return $this;
    }
}