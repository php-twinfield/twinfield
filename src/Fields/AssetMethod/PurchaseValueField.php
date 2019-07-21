<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait PurchaseValueField
{
    /**
     * @var GeneralLedger|null
     */
    private $purchaseValue;

    public function getPurchaseValue(): ?GeneralLedger
    {
        return $this->purchaseValue;
    }

    /**
     * @return $this
     */
    public function setPurchaseValue(?GeneralLedger $purchaseValue): self
    {
        $this->purchaseValue = $purchaseValue;
        return $this;
    }
}
