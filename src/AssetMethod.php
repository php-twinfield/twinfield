<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethod extends BaseObject
{
    use OfficeField;

    private $calcMethod;
    private $code;
    private $created;
    private $depreciateReconciliation;
    private $inUse;
    private $modified;
    private $name;
    private $nrOfPeriods;
    private $percentage;
    private $shortName;
    private $status;
    private $touched;
    private $user;
    private $balanceAccounts;
    private $profitLossAccounts;
    private $freeTexts = [];

    public function getCalcMethod()
    {
        return $this->calcMethod;
    }

    public function setCalcMethod($calcMethod)
    {
        $this->calcMethod = $calcMethod;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getDepreciateReconciliation()
    {
        return $this->depreciateReconciliation;
    }

    public function setDepreciateReconciliation($depreciateReconciliation)
    {
        $this->depreciateReconciliation = $depreciateReconciliation;
        return $this;
    }

    public function getInUse()
    {
        return $this->inUse;
    }

    public function setInUse($inUse)
    {
        $this->inUse = $inUse;
        return $this;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getNrOfPeriods()
    {
        return $this->nrOfPeriods;
    }

    public function setNrOfPeriods($nrOfPeriods)
    {
        $this->nrOfPeriods = $nrOfPeriods;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getTouched()
    {
        return $this->touched;
    }

    public function setTouched($touched)
    {
        $this->touched = $touched;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getBalanceAccounts(): AssetMethodBalanceAccounts
    {
        return $this->balanceAccounts;
    }

    public function setBalanceAccounts(AssetMethodBalanceAccounts $balanceAccounts)
    {
        $this->balanceAccounts = $balanceAccounts;
        return $this;
    }

    public function getProfitLossAccounts(): AssetMethodProfitLossAccounts
    {
        return $this->profitLossAccounts;
    }

    public function setProfitLossAccounts(AssetMethodProfitLossAccounts $profitLossAccounts)
    {
        $this->profitLossAccounts = $profitLossAccounts;
        return $this;
    }

    public function getFreeTexts()
    {
        return $this->freeTexts;
    }

    public function addFreeText(AssetMethodFreeText $freeText)
    {
        $this->freeTexts[$freeText->getID()] = $freeText;
        return $this;
    }

    public function removeFreeText($index)
    {
        if (array_key_exists($index, $this->freeTexts)) {
            unset($this->freeTexts[$index]);
            return true;
        } else {
            return false;
        }
    }
}
