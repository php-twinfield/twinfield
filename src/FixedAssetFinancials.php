<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetFinancials
{
    private $accountType;
    private $level;
    private $matchType;
    private $subAnalyse;
    private $substitutionLevel;
    private $substituteWith;
    private $vatCode;

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

    public function getSubstitutionLevel()
    {
        return $this->substitutionLevel;
    }

    public function setSubstitutionLevel($substitutionLevel)
    {
        $this->substitutionLevel = $substitutionLevel;
        return $this;
    }

    public function getSubstituteWith()
    {
        return $this->substituteWith;
    }

    public function setSubstituteWith($substituteWith)
    {
        $this->substituteWith = $substituteWith;
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
}
