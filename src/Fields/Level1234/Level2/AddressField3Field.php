<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait AddressField3Field
{
    /**
     * Address field 3 field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $field3;

    /**
     * @return null|string
     */
    public function getField3(): ?string
    {
        return $this->field3;
    }

    /**
     * @param null|string $field3
     * @return $this
     */
    public function setField3(?string $field3): self
    {
        $this->field3 = $field3;
        return $this;
    }
}