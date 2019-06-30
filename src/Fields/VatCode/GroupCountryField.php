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

    /**
     * @return $this
     */
    public function setGroupCountry(?VatGroupCountry $groupCountry): self
    {
        $this->groupCountry = $groupCountry;
        return $this;
    }
}
