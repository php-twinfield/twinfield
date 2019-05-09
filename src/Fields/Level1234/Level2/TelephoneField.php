<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait TelephoneField
{
    /**
     * Telephone field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $telephone;

    /**
     * @return null|string
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param null|string $telephone
     * @return $this
     */
    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }
}