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

    /**
     * @param bool $allowChangeUnitsPrice
     * @return $this
     */
    public function setAllowChangeUnitsPrice(?bool $allowChangeUnitsPrice): self
    {
        $this->allowChangeUnitsPrice = $allowChangeUnitsPrice;
        return $this;
    }
}
