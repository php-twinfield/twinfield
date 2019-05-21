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

    public function getCountryToString(): ?string
    {
        if ($this->getCountry() != null) {
            return $this->country->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string|null $countryString
     * @return $this
     * @throws Exception
     */
    public function setCountryFromString(?string $countryString)
    {
        $country = new Country();
        $country->setCode($countryString);
        return $this->setCountry($country);
    }
}
