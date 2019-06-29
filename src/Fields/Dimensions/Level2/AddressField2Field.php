<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait AddressField2Field
{
    /**
     * Address field 2 field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $addressField2;

    /**
     * @return null|string
     */
    public function getAddressField2(): ?string
    {
        return $this->addressField2;
    }

    /**
     * @param null|string $addressField2
     * @return $this
     */
    public function setAddressField2(?string $addressField2): self
    {
        $this->addressField2 = $addressField2;
        return $this;
    }
}
