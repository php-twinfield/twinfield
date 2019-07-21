<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts, AssetMethodProfitLossAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait ReconciliationField
{
    /**
     * @var GeneralLedger|null
     */
    private $reconciliation;

    public function getReconciliation(): ?GeneralLedger
    {
        return $this->reconciliation;
    }

    /**
     * @return $this
     */
    public function setReconciliation(?GeneralLedger $reconciliation): self
    {
        $this->reconciliation = $reconciliation;
        return $this;
    }
}
