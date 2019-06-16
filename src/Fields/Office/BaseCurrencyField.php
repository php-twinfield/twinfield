<?php

namespace PhpTwinfield\Fields;

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

    public function getBaseCurrencyToString(): ?string
    {
        if ($this->getBaseCurrency() != null) {
            return $this->baseCurrency->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setBaseCurrency(?Currency $baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }

    /**
     * @param string|null $baseCurrencyString
     * @return $this
     * @throws Exception
     */
    public function setBaseCurrencyFromString(?string $baseCurrencyString)
    {
        $baseCurrency = new Currency();
        $baseCurrency->setCode($baseCurrencyString);
        return $this->setBaseCurrency($baseCurrency);
    }
}