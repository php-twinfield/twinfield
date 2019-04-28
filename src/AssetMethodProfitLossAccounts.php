<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethodProfitLossAccounts
{
    private $depreciation;
    private $sales;
    private $reconciliation;

    public function getDepreciation()
    {
        return $this->depreciation;
    }

    public function setDepreciation($depreciation)
    {
        $this->depreciation = $depreciation;
        return $this;
    }

    public function getSales()
    {
        return $this->sales;
    }

    public function setSales($sales)
    {
        $this->sales = $sales;
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
}
