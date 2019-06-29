<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Currency;

/**
 * The currency
 * Used by: BaseTransaction, CustomerPostingRule, ElectronicBankStatement, Invoice, Rate, SupplierPostingRule
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

    /**
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }
}
