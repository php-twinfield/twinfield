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

    public function getPerformanceCountryToString(): ?string
    {
        if ($this->getPerformanceCountry() != null) {
            return $this->performanceCountry->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setPerformanceCountry(?Country $performanceCountry): self
    {
        $this->performanceCountry = $performanceCountry;
        return $this;
    }

    /**
     * @param string|null $performanceCountry
     * @return $this
     * @throws Exception
     */
    public function setPerformanceCountryFromString(?string $performanceCountry)
    {
        $performanceCountry = new Country();
        $performanceCountry->setCode($performanceCountry);
        return $this->setPerformanceCountry($performanceCountry);
    }
}