<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

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

    public function getCountryToCode(): ?string
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
     * @param string|null $countryCode
     * @return $this
     * @throws Exception
     */
    public function setCountryFromCode(?string $countryCode)
    {
        $country = new Country();
        $country->setCode($countryCode);
        return $this->setCountry($country);
    }
}
