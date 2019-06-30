<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;

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
     * @param Money|null $valueInc
     * @return $this
     */
    public function setValueInc(?Money $valueInc)
    {
        $this->valueInc = $valueInc;

        return $this;
    }
}
