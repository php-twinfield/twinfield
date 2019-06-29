<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;

trait UnitsPriceIncField
{
    /**
     * Units price inc field
     * Used by: ArticleLine, InvoiceLine
     *
     * @var Money|null
     */
    private $unitsPriceInc;

    /**
     * @return Money|null
     */
    public function getUnitsPriceInc(): ?Money
    {
        return $this->unitsPriceInc;
    }

    /**
     * @param Money|null $unitsPriceInc
     * @return $this
     */
    public function setUnitsPriceInc(?Money $unitsPriceInc)
    {
        $this->unitsPriceInc = $unitsPriceInc;

        return $this;
    }
}
