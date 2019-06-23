<?php

namespace PhpTwinfield\Fields\Office;

use PhpTwinfield\Currency;

/**
 * The currency
 * Used by: Office
 *
 * @package PhpTwinfield\Traits
 */
trait BaseCurrencyField
{
    /**
     * @var Currency|null
     */
    private $baseCurrency;

    public function getBaseCurrency(): ?Currency
    {
        return $this->baseCurrency;
    }

    /**
     * @return $this
     */
    public function setBaseCurrency(?Currency $baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }
}