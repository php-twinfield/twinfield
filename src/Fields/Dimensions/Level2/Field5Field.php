<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait Field5Field
{
    /**
     * Field 5 field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $field5;

    /**
     * @return null|string
     */
    public function getField5(): ?string
    {
        return $this->field5;
    }

    /**
     * @param null|string $field5
     * @return $this
     */
    public function setField5(?string $field5): self
    {
        $this->field5 = $field5;
        return $this;
    }
}