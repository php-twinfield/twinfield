<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

trait VatValueField
{
    /**
     * VAT value field
     * Used by: InvoiceLine, InvoiceVatLine
     *
     * @var Money|null
     */
    private $vatValue;

    /**
     * @return Money|null
     */
    public function getVatValue(): ?Money
    {
        return $this->vatValue;
    }

    /**
     * @return float|null
     */
    public function getVatValueToFloat(): ?float
    {
        if ($this->getVatValue() != null) {
            return Util::formatMoney($this->getVatValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatValue
     * @return $this
     */
    public function setVatValue(?Money $vatValue)
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * @param float|null $vatValueFloat
     * @return $this
     * @throws Exception
     */
    public function setVatValueFromFloat(?float $vatValueFloat)
    {
        if ((float)$vatValueFloat) {
            return $this->setVatValue(Money::EUR(100 * $vatValueFloat));
        } else {
            return $this->setVatValue(Money::EUR(0));
        }
    }
}