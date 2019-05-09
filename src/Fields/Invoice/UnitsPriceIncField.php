<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

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
     * @return float|null
     */
    public function getUnitsPriceIncToFloat(): ?float
    {
        if ($this->getUnitsPriceInc() != null) {
            return Util::formatMoney($this->getUnitsPriceInc());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $unitsPriceIncFloat
     * @return $this
     * @throws Exception
     */
    public function setUnitsPriceIncFromFloat(?float $unitsPriceIncFloat)
    {
        if ((float)$unitsPriceIncFloat) {
            return $this->setUnitsPriceInc(Money::EUR(100 * $unitsPriceIncFloat));
        } else {
            return $this->setUnitsPriceInc(Money::EUR(0));
        }
    }
}