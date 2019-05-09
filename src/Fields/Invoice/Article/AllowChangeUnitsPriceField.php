<?php

namespace PhpTwinfield\Fields\Invoice\Article;

trait AllowChangeUnitsPriceField
{
    /**
     * Allow change units price field
     * Used by: Article
     *
     * @var bool
     */
    private $allowChangeUnitsPrice;

    /**
     * @return bool
     */
    public function getAllowChangeUnitsPrice(): ?bool
    {
        return $this->allowChangeUnitsPrice;
    }

    public function getAllowChangeUnitsPriceToString(): ?string
    {
        return ($this->getAllowChangeUnitsPrice()) ? 'true' : 'false';
    }

    /**
     * @param bool $allowChangeUnitsPrice
     * @return $this
     */
    public function setAllowChangeUnitsPrice(?bool $allowChangeUnitsPrice): self
    {
        $this->allowChangeUnitsPrice = $allowChangeUnitsPrice;
        return $this;
    }

    /**
     * @param string|null $allowChangeUnitsPriceString
     * @return $this
     * @throws Exception
     */
    public function setAllowChangeUnitsPriceFromString(?string $allowChangeUnitsPriceString)
    {
        return $this->setAllowChangeUnitsPrice(filter_var($allowChangeUnitsPriceString, FILTER_VALIDATE_BOOLEAN));
    }
}