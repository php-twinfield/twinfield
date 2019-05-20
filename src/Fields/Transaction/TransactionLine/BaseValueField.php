<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait BaseValueField
{
    /**
     * Base value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $baseValue;

    /**
     * @return Money|null
     */
    public function getBaseValue(): ?Money
    {
        return $this->baseValue;
    }

    /**
     * @return float|null
     */
    public function getBaseValueToFloat(): ?float
    {
        if ($this->getBaseValue() != null) {
            return Util::formatMoney($this->getBaseValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $baseValue
     * @return $this
     */
    public function setBaseValue(?Money $baseValue)
    {
        $this->baseValue = $baseValue;

        return $this;
    }

    /**
     * @param float|null $baseValueFloat
     * @return $this
     * @throws Exception
     */
    public function setBaseValueFromFloat(?float $baseValueFloat)
    {
        if ((float)$baseValueFloat) {
            return $this->setBaseValue(Money::EUR(100 * $baseValueFloat));
        } else {
            return $this->setBaseValue(Money::EUR(0));
        }
    }
}