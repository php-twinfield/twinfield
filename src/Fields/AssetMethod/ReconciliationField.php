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

    public function getReconciliationToString(): ?string
    {
        if ($this->getReconciliation() != null) {
            return $this->reconciliation->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setReconciliation(?GeneralLedger $reconciliation): self
    {
        $this->reconciliation = $reconciliation;
        return $this;
    }

    /**
     * @param string|null $reconciliationString
     * @return $this
     * @throws Exception
     */
    public function setReconciliationFromString(?string $reconciliationString)
    {
        $reconciliation = new GeneralLedger();
        $reconciliation->setCode($reconciliationString);
        return $this->setReconciliation($reconciliation);
    }
}