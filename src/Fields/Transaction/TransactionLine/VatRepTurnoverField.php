<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;

trait VatRepTurnoverField
{
    /**
     * Vat rep turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatRepTurnover;

    /**
     * @return Money|null
     */
    public function getVatRepTurnover(): ?Money
    {
        return $this->vatRepTurnover;
    }

    /**
     * @param Money|null $vatRepTurnover
     * @return $this
     */
    public function setVatRepTurnover(?Money $vatRepTurnover)
    {
        $this->vatRepTurnover = $vatRepTurnover;

        return $this;
    }
}