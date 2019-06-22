<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\DimensionGroup;

/**
 * The dimension group
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait PurchaseValueGroupField
{
    /**
     * @var DimensionGroup|null
     */
    private $purchaseValueGroup;

    public function getPurchaseValueGroup(): ?DimensionGroup
    {
        return $this->purchaseValueGroup;
    }

    /**
     * @return $this
     */
    public function setPurchaseValueGroup(?DimensionGroup $purchaseValueGroup): self
    {
        $this->purchaseValueGroup = $purchaseValueGroup;
        return $this;
    }
}