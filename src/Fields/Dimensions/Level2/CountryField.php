<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

use PhpTwinfield\Country;

/**
 * The country.
 * Used by: CustomerAddress, CustomerBank, SupplierAddress, SupplierBank
 *
 * @package PhpTwinfield\Traits
 */
trait CountryField
{
    /**
     * @var Country|null
     */
    private $country;

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @return $this
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;
        return $this;
    }
}
