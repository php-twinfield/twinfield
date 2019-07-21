<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatBaseTurnoverField
{
    /**
     * VAT base turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseTurnover;

    /**
     * @return Money|null
     */
    public function getVatBaseTurnover(): ?Money
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @param Money|null $vatBaseTurnover
     * @return $this
     */
    public function setVatBaseTurnover(?Money $vatBaseTurnover)
    {
        $this->vatBaseTurnover = $vatBaseTurnover;

        return $this;
    }
}
