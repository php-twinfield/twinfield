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
    private $addressField3;

    /**
     * @return null|string
     */
    public function getAddressField3(): ?string
    {
        return $this->addressField3;
    }

    /**
     * @param null|string $addressField3
     * @return $this
     */
    public function setAddressField3(?string $addressField3): self
    {
        $this->addressField3 = $addressField3;
        return $this;
    }
}