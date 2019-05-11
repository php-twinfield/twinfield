<?php

namespace PhpTwinfield\Fields\VatCode;

use PhpTwinfield\Country;

/**
 * The country
 * Used by: VatCodeAccount
 *
 * @package PhpTwinfield\Traits
 */
trait GroupCountryField
{
    /**
     * @var Country|null
     */
    private $groupCountry;

    public function getGroupCountry(): ?Country
    {
        return $this->groupCountry;
    }

    public function getGroupCountryToString(): ?string
    {
        if ($this->getGroupCountry() != null) {
            return $this->groupCountry->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setGroupCountry(?Country $groupCountry): self
    {
        $this->groupCountry = $groupCountry;
        return $this;
    }

    /**
     * @param string|null $groupCountryCode
     * @return $this
     * @throws Exception
     */
    public function setGroupCountryFromString(?string $groupCountryCode)
    {
        $groupCountry = new Country();
        $groupCountry->setCode($groupCountryCode);
        return $this->setGroupCountry($groupCountry);
    }
}