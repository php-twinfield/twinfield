<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait ValueOpenField
{
    /**
     * Value open field
     * Used by: PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $valueOpen;

    /**
     * @return Money|null
     */
    public function getValueOpen(): ?Money
    {
        return $this->valueOpen;
    }

    /**
     * @return float|null
     */
    public function getValueOpenToFloat(): ?float
    {
        if ($this->getValueOpen() != null) {
            return Util::formatMoney($this->getValueOpen());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $valueOpen
     * @return $this
     */
    public function setValueOpen(?Money $valueOpen)
    {
        $this->valueOpen = $valueOpen;

        return $this;
    }

    /**
     * @param float|null $valueOpenFloat
     * @return $this
     * @throws Exception
     */
    public function setValueOpenFromFloat(?float $valueOpenFloat)
    {
        if ((float)$valueOpenFloat) {
            return $this->setValueOpen(Money::EUR(100 * $valueOpenFloat));
        } else {
            return $this->setValueOpen(Money::EUR(0));
        }
    }
}