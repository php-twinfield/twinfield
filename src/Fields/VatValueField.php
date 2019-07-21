<?php

namespace PhpTwinfield\Fields;

use Money\Money;

trait VatValueField
{
    /**
     * VAT value field
     * Used by: BaseTransactionLine, InvoiceLine, InvoiceVatLine
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
     * @param Money|null $vatValue
     * @return $this
     */
    public function setVatValue(?Money $vatValue)
    {
        $this->vatValue = $vatValue;

        return $this;
    }
}
