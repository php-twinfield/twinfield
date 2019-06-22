<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Country;

/**
 * The country
 * Used by: BankTransactionLine, CashTransactionLine, JournalTransactionLine, SalesTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait PerformanceCountryField
{
    /**
     * @var Country|null
     */
    private $performanceCountry;

    public function getPerformanceCountry(): ?Country
    {
        return $this->performanceCountry;
    }

    /**
     * @return $this
     */
    public function setPerformanceCountry(?Country $performanceCountry): self
    {
        $this->performanceCountry = $performanceCountry;
        return $this;
    }
}