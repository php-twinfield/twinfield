<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatTurnoverField
{
    /**
     * Vat turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatTurnover;

    /**
     * @return Money|null
     */
    public function getVatTurnover(): ?Money
    {
        return $this->vatTurnover;
    }

    /**
     * @param Money|null $vatTurnover
     * @return $this
     */
    public function setVatTurnover(?Money $vatTurnover)
    {
        $this->vatTurnover = $vatTurnover;

        return $this;
    }
}