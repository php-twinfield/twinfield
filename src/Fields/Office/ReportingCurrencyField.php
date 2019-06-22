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
    
    /**
     * @return $this
     */
    public function setReportingCurrency(?Currency $reportingCurrency): self
    {
        $this->reportingCurrency = $reportingCurrency;
        return $this;
    }
}