<?php

namespace PhpTwinfield\Fields\Office;

use PhpTwinfield\Country;

/**
 * The country
 * Used by: Office
 *
 * @package PhpTwinfield\Traits
 */
trait CountryCodeField
{
    /**
     * @var Country|null
     */
    private $countryCode;

    public function getCountryCode(): ?Country
    {
        return $this->countryCode;
    }

    public function getCountryCodeToString(): ?string
    {
        if ($this->getCountryCode() != null) {
            return $this->countryCode->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setCountryCode(?Country $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @param string|null $countryCodeString
     * @return $this
     * @throws Exception
     */
    public function setCountryCodeFromString(?string $countryCodeString)
    {
        $countryCode = new Country();
        $countryCode->setCode($countryCodeString);
        return $this->setCountryCode($countryCode);
    }
}