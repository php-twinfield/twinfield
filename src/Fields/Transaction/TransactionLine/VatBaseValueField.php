<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatBaseValueField
{
    /**
     * Vat base value field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseValue;

    /**
     * @return Money|null
     */
    public function getVatBaseValue(): ?Money
    {
        return $this->vatBaseValue;
    }

    /**
     * @return float|null
     */
    public function getVatBaseValueToFloat(): ?float
    {
        if ($this->getVatBaseValue() != null) {
            return Util::formatMoney($this->getVatBaseValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatBaseValue
     * @return $this
     */
    public function setVatBaseValue(?Money $vatBaseValue)
    {
        $this->vatBaseValue = $vatBaseValue;

        return $this;
    }

    /**
     * @param float|null $vatBaseValueFloat
     * @return $this
     * @throws Exception
     */
    public function setVatBaseValueFromFloat(?float $vatBaseValueFloat)
    {
        if ((float)$vatBaseValueFloat) {
            return $this->setVatBaseValue(Money::EUR(100 * $vatBaseValueFloat));
        } else {
            return $this->setVatBaseValue(Money::EUR(0));
        }
    }
}