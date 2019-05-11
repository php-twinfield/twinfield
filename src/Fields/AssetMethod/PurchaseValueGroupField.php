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

    public function getPurchaseValueGroupToString(): ?string
    {
        if ($this->getPurchaseValueGroup() != null) {
            return $this->purchaseValueGroup->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setPurchaseValueGroup(?DimensionGroup $purchaseValueGroup): self
    {
        $this->purchaseValueGroup = $purchaseValueGroup;
        return $this;
    }

    /**
     * @param string|null $purchaseValueGroupCode
     * @return $this
     * @throws Exception
     */
    public function setPurchaseValueGroupFromString(?string $purchaseValueGroupCode)
    {
        $purchaseValueGroup = new DimensionGroup();
        $purchaseValueGroup->setCode($purchaseValueGroupCode);
        return $this->setPurchaseValueGroup($purchaseValueGroup);
    }
}