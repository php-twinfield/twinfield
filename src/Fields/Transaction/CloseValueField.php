<?php

namespace PhpTwinfield\Fields\Transaction;

use Money\Money;
use PhpTwinfield\Util;

trait CloseValueField
{
    /**
     * Close value field
     * Used by: BankTransaction, CashTransaction, ElectronicBankStatement
     *
     * @var Money|null
     */
    private $closeValue;

    /**
     * @return Money|null
     */
    public function getCloseValue(): ?Money
    {
        return $this->closeValue;
    }

    /**
     * @return float|null
     */
    public function getCloseValueToFloat(): ?float
    {
        if ($this->getCloseValue() != null) {
            return Util::formatMoney($this->getCloseValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $closeValue
     * @return $this
     */
    public function setCloseValue(?Money $closeValue)
    {
        $this->closeValue = $closeValue;

        return $this;
    }

    /**
     * @param float|null $closeValueFloat
     * @return $this
     * @throws Exception
     */
    public function setCloseValueFromFloat(?float $closeValueFloat)
    {
        if ((float)$closeValueFloat) {
            return $this->setCloseValue(Money::EUR(100 * $closeValueFloat));
        } else {
            return $this->setCloseValue(Money::EUR(0));
        }
    }
}