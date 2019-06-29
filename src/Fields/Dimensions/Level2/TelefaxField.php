<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait TelefaxField
{
    /**
     * Telefax field
     * Used by: CustomerAddress, SupplierAddress
     *
     * @var string|null
     */
    private $telefax;

    /**
     * @return null|string
     */
    public function getTelefax(): ?string
    {
        return $this->telefax;
    }

    /**
     * @param null|string $telefax
     * @return $this
     */
    public function setTelefax(?string $telefax): self
    {
        $this->telefax = $telefax;
        return $this;
    }
}
