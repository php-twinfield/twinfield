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
