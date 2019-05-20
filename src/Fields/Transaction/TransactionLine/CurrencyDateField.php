<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Currency date field
 * Used by: BankTransactionLine, CashTransactionLine, JournalTransactionLine
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
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
     * @return string|null
     */
    public function getCurrencyDateToString(): ?string
    {
        if ($this->getCurrencyDate() != null) {
            return Util::formatDateTime($this->getCurrencyDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $currencyDateString
     * @return $this
     * @throws Exception
     */
    public function setCurrencyDateFromString(?string $currencyDateString)
    {
        if ((bool)strtotime($currencyDateString)) {
            return $this->setCurrencyDate(Util::parseDateTime($currencyDateString));
        } else {
            return $this->setCurrencyDate(null);
        }
    }
}