<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethodBalanceAccounts
{
    private $purchaseValueGroup;
    private $purchaseValue;
    private $depreciationGroup;
    private $depreciation;
    private $assetsToActivate;
    private $reconciliation;
    private $toBeInvoiced;

    public function getPurchaseValueGroup()
    {
        return $this->purchaseValueGroup;
    }

    public function setPurchaseValueGroup($purchaseValueGroup)
    {
        $this->purchaseValueGroup = $purchaseValueGroup;
        return $this;
    }

    public function getPurchaseValue()
    {
        return $this->purchaseValue;
    }

    public function setPurchaseValue($purchaseValue)
    {
        $this->purchaseValue = $purchaseValue;
        return $this;
    }

    public function getDepreciationGroup()
    {
        return $this->depreciationGroup;
    }

    public function setDepreciationGroup($depreciationGroup)
    {
        $this->depreciationGroup = $depreciationGroup;
        return $this;
    }

    public function getDepreciation()
    {
        return $this->depreciation;
    }

    public function setDepreciation($depreciation)
    {
        $this->depreciation = $depreciation;
        return $this;
    }

    public function getAssetsToActivate()
    {
        return $this->assetsToActivate;
    }

    public function setAssetsToActivate($assetsToActivate)
    {
        $this->assetsToActivate = $assetsToActivate;
        return $this;
    }

    public function getReconciliation()
    {
        return $this->reconciliation;
    }

    public function setReconciliation($reconciliation)
    {
        $this->reconciliation = $reconciliation;
        return $this;
    }

    public function getToBeInvoiced()
    {
        return $this->toBeInvoiced;
    }

    public function setToBeInvoiced($toBeInvoiced)
    {
        $this->toBeInvoiced = $toBeInvoiced;
        return $this;
    }
}
