<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait AddressField2Field
{
    /**
     * Address field 2 field
     * Used by: CustomerBank, SupplierBank
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