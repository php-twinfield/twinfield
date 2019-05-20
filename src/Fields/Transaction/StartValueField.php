<?php

namespace PhpTwinfield\Fields\Transaction;

use Money\Money;
use PhpTwinfield\Util;

trait StartValueField
{
    /**
     * Start value field
     * Used by: BankTransaction, CashTransaction, ElectronicBankStatement
     *
     * @var Money|null
     */
    private $startValue;

    /**
     * @return Money|null
     */
    public function getStartValue(): ?Money
    {
        return $this->startValue;
    }

    /**
     * @return float|null
     */
    public function getStartValueToFloat(): ?float
    {
        if ($this->getStartValue() != null) {
            return Util::formatMoney($this->getStartValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $startValue
     * @return $this
     */
    public function setStartValue(?Money $startValue)
    {
        $this->startValue = $startValue;

        return $this;
    }

    /**
     * @param float|null $startValueFloat
     * @return $this
     * @throws Exception
     */
    public function setStartValueFromFloat(?float $startValueFloat)
    {
        if ((float)$startValueFloat) {
            return $this->setStartValue(Money::EUR(100 * $startValueFloat));
        } else {
            return $this->setStartValue(Money::EUR(0));
        }
    }
}