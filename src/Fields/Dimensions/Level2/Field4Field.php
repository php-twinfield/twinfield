<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait Field4Field
{
    /**
     * Field 4 field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $field4;

    /**
     * @return null|string
     */
    public function getField4(): ?string
    {
        return $this->field4;
    }

    /**
     * @param null|string $field4
     * @return $this
     */
    public function setField4(?string $field4): self
    {
        $this->field4 = $field4;
        return $this;
    }
}
