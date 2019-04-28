<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets
 * @todo Add documentation and typehints to all properties.
 */
class GeneralLedgerFinancials
{
    private $accountType;
    private $level;
    private $matchType;
    private $subAnalyse;
    private $vatCode;
    private $childValidations = [];

    public function getAccountType()
    {
        return $this->accountType;
    }

    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function getMatchType()
    {
        return $this->matchType;
    }

    public function setMatchType($matchType)
    {
        $this->matchType = $matchType;
        return $this;
    }

    public function getSubAnalyse()
    {
        return $this->subAnalyse;
    }

    public function setSubAnalyse($subAnalyse)
    {
        $this->subAnalyse = $subAnalyse;
        return $this;
    }

    public function getVatCode()
    {
        return $this->vatCode;
    }

    public function setVatCode($vatCode)
    {
        $this->vatCode = $vatCode;
        return $this;
    }

    public function getChildValidations()
    {
        return $this->childValidations;
    }

    public function addChildValidation(GeneralLedgerChildValidation $childValidation)
    {
        $this->childValidations[] = $childValidation;
        return $this;
    }

    public function removeChildValidation($index)
    {
        if (array_key_exists($index, $this->childValidations)) {
            unset($this->childValidations[$index]);
            return true;
        } else {
            return false;
        }
    }
}
