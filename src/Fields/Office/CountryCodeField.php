<?php

namespace PhpTwinfield\Fields\Office;

trait CountryCodeField
{
    /**
     * Country code field
     * Used by: Office
     *
     * @var string|null
     */
    private $countryCode;

    /**
     * @return null|string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param null|string $countryCode
     * @return $this
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }
}