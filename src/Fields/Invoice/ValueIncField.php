<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

trait ValueIncField
{
    /**
     * Value incl field
     * Used by: InvoiceLine, InvoiceTotals
     *
     * @var Money|null
     */
    private $valueInc;

    /**
     * @return Money|null
     */
    public function getValueInc(): ?Money
    {
        return $this->valueInc;
    }

    /**
     * @return float|null
     */
    public function getValueIncToFloat(): ?float
    {
        if ($this->getValueInc() != null) {
            return Util::formatMoney($this->getValueInc());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $valueInc
     * @return $this
     */
    public function setValueInc(?Money $valueInc)
    {
        $this->valueInc = $valueInc;

        return $this;
    }

    /**
     * @param float|null $valueIncFloat
     * @return $this
     * @throws Exception
     */
    public function setValueIncFromFloat(?float $valueIncFloat)
    {
        if ((float)$valueIncFloat) {
            return $this->setValueInc(Money::EUR(100 * $valueIncFloat));
        } else {
            return $this->setValueInc(Money::EUR(0));
        }
    }
}