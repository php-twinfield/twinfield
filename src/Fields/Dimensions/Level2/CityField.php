<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait CityField
{
    /**
     * City field
     * Used by: CustomerAddress, CustomerBank, SupplierAddress, SupplierBank
     *
     * @var string|null
     */
    private $city;

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }
}