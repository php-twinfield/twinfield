<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

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
     * @return float|null
     */
    public function getUnitsPriceExclToFloat(): ?float
    {
        if ($this->getUnitsPriceExcl() != null) {
            return Util::formatMoney($this->getUnitsPriceExcl());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $unitsPriceExclFloat
     * @return $this
     * @throws Exception
     */
    public function setUnitsPriceExclFromFloat(?float $unitsPriceExclFloat)
    {
        if ((float)$unitsPriceExclFloat) {
            return $this->setUnitsPriceExcl(Money::EUR(100 * $unitsPriceExclFloat));
        } else {
            return $this->setUnitsPriceExcl(Money::EUR(0));
        }
    }
}