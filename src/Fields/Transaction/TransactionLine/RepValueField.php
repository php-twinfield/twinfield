<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait RepValueField
{
    /**
     * Rep value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $repValue;

    /**
     * @return Money|null
     */
    public function getRepValue(): ?Money
    {
        return $this->repValue;
    }

    /**
     * @return float|null
     */
    public function getRepValueToFloat(): ?float
    {
        if ($this->getRepValue() != null) {
            return Util::formatMoney($this->getRepValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $repValue
     * @return $this
     */
    public function setRepValue(?Money $repValue)
    {
        $this->repValue = $repValue;

        return $this;
    }

    /**
     * @param float|null $repValueFloat
     * @return $this
     * @throws Exception
     */
    public function setRepValueFromFloat(?float $repValueFloat)
    {
        if ((float)$repValueFloat) {
            return $this->setRepValue(Money::EUR(100 * $repValueFloat));
        } else {
            return $this->setRepValue(Money::EUR(0));
        }
    }
}