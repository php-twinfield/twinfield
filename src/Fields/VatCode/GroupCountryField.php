<?php

namespace PhpTwinfield\Fields\VatCode;

use PhpTwinfield\VatGroupCountry;

/**
 * The VAT group country
 * Used by: VatCodeAccount
 *
 * @package PhpTwinfield\Traits
 */
trait GroupCountryField
{
    /**
     * @var VatGroupCountry|null
     */
    private $groupCountry;

    public function getGroupCountry(): ?VatGroupCountry
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
    public function setGroupCountry(?VatGroupCountry $groupCountry): self
    {
        $this->groupCountry = $groupCountry;
        return $this;
    }

    /**
     * @param string|null $groupCountryString
     * @return $this
     * @throws Exception
     */
    public function setGroupCountryFromString(?string $groupCountryString)
    {
        $groupCountry = new VatGroupCountry();
        $groupCountry->setCode($groupCountryString);
        return $this->setGroupCountry($groupCountry);
    }
}