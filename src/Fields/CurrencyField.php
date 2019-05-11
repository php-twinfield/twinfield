<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Currency;

/**
 * The currency
 * Used by: CustomerPostingRule, Invoice, Rate, SupplierPostingRule
 *
 * @package PhpTwinfield\Traits
 */
trait CurrencyField
{
    /**
     * @var Currency|null
     */
    private $currency;

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function getCurrencyToString(): ?string
    {
        if ($this->getCurrency() != null) {
            return $this->currency->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string|null $currencyCode
     * @return $this
     * @throws Exception
     */
    public function setCurrencyFromString(?string $currencyCode)
    {
        $currency = new Currency();
        $currency->setCode($currencyCode);
        return $this->setCurrency($currency);
    }
}