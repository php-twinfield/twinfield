<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;

trait UnitsPriceExclField
{
    /**
     * Units price excl field
     * Used by: ArticleLine, InvoiceLine
     *
     * @var Money|null
     */
    private $unitsPriceExcl;

    /**
     * @return Money|null
     */
    public function getUnitsPriceExcl(): ?Money
    {
        return $this->unitsPriceExcl;
    }

    /**
     * @param Money|null $unitsPriceExcl
     * @return $this
     */
    public function setUnitsPriceExcl(?Money $unitsPriceExcl)
    {
        $this->unitsPriceExcl = $unitsPriceExcl;

        return $this;
    }
}
