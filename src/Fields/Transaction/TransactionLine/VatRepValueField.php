<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatRepValueField
{
    /**
     * VAT rep value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatRepValue;

    /**
     * @return Money|null
     */
    public function getVatRepValue(): ?Money
    {
        return $this->vatRepValue;
    }

    /**
     * @return float|null
     */
    public function getVatRepValueToFloat(): ?float
    {
        if ($this->getVatRepValue() != null) {
            return Util::formatMoney($this->getVatRepValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatRepValue
     * @return $this
     */
    public function setVatRepValue(?Money $vatRepValue)
    {
        $this->vatRepValue = $vatRepValue;

        return $this;
    }

    /**
     * @param float|null $vatRepValueFloat
     * @return $this
     * @throws Exception
     */
    public function setVatRepValueFromFloat(?float $vatRepValueFloat)
    {
        if ((float)$vatRepValueFloat) {
            return $this->setVatRepValue(Money::EUR(100 * $vatRepValueFloat));
        } else {
            return $this->setVatRepValue(Money::EUR(0));
        }
    }
}