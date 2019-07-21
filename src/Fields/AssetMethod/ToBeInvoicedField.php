<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait ToBeInvoicedField
{
    /**
     * @var GeneralLedger|null
     */
    private $toBeInvoiced;

    public function getToBeInvoiced(): ?GeneralLedger
    {
        return $this->toBeInvoiced;
    }

    /**
     * @return $this
     */
    public function setToBeInvoiced(?GeneralLedger $toBeInvoiced): self
    {
        $this->toBeInvoiced = $toBeInvoiced;
        return $this;
    }
}
