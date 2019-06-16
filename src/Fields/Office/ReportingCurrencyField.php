<?php

namespace PhpTwinfield\Fields\Office;

use PhpTwinfield\Currency;

/**
 * The currency
 * Used by: Office
 *
 * @package PhpTwinfield\Traits
 */
trait ReportingCurrencyField
{
    /**
     * @var Currency|null
     */
    private $reportingCurrency;

    public function getReportingCurrency(): ?Currency
    {
        return $this->reportingCurrency;
    }

    public function getReportingCurrencyToString(): ?string
    {
        if ($this->getReportingCurrency() != null) {
            return $this->reportingCurrency->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setReportingCurrency(?Currency $reportingCurrency): self
    {
        $this->reportingCurrency = $reportingCurrency;
        return $this;
    }

    /**
     * @param string|null $reportingCurrencyString
     * @return $this
     * @throws Exception
     */
    public function setReportingCurrencyFromString(?string $reportingCurrencyString)
    {
        $reportingCurrency = new Currency();
        $reportingCurrency->setCode($reportingCurrencyString);
        return $this->setReportingCurrency($reportingCurrency);
    }
}