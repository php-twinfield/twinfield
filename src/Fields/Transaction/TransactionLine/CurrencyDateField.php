<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

/**
 * Currency date field
 * Used by: BankTransactionLine, CashTransactionLine, JournalTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait CurrencyDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $currencyDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCurrencyDate(): ?\DateTimeInterface
    {
        return $this->currencyDate;
    }

    /**
     * @param \DateTimeInterface|null $currencyDate
     * @return $this
     */
    public function setCurrencyDate(?\DateTimeInterface $currencyDate)
    {
        $this->currencyDate = $currencyDate;
        return $this;
    }
}
