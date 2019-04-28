<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetFixedAssets
{
    private $beginPeriod;
    private $freeText1;
    private $freeText2;
    private $freeText3;
    private $freeText4;
    private $freeText5;
    private $lastDepreciation;
    private $method;
    private $nrOfPeriods;
    private $percentage;
    private $purchaseDate;
    private $residualValue;
    private $sellDate;
    private $status;
    private $stopValue;
    private $transactionLines = [];

    public function getBeginPeriod()
    {
        return $this->beginPeriod;
    }

    public function setBeginPeriod($beginPeriod)
    {
        $this->beginPeriod = $beginPeriod;
        return $this;
    }

    public function getFreeText1()
    {
        return $this->freeText1;
    }

    public function setFreeText1($freeText1)
    {
        $this->freeText1 = $freeText1;
        return $this;
    }

    public function getFreeText2()
    {
        return $this->freeText2;
    }

    public function setFreeText2($freeText2)
    {
        $this->freeText2 = $freeText2;
        return $this;
    }

    public function getFreeText3()
    {
        return $this->freeText3;
    }

    public function setFreeText3($freeText3)
    {
        $this->freeText3 = $freeText3;
        return $this;
    }

    public function getFreeText4()
    {
        return $this->freeText4;
    }

    public function setFreeText4($freeText4)
    {
        $this->freeText4 = $freeText4;
        return $this;
    }

    public function getFreeText5()
    {
        return $this->freeText5;
    }

    public function setFreeText5($freeText5)
    {
        $this->freeText5 = $freeText5;
        return $this;
    }

    public function getLastDepreciation()
    {
        return $this->lastDepreciation;
    }

    public function setLastDepreciation($lastDepreciation)
    {
        $this->lastDepreciation = $lastDepreciation;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
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

    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    public function getResidualValue()
    {
        return $this->residualValue;
    }

    public function setResidualValue($residualValue)
    {
        $this->residualValue = $residualValue;
        return $this;
    }

    public function getSellDate()
    {
        return $this->sellDate;
    }

    public function setSellDate($sellDate)
    {
        $this->sellDate = $sellDate;
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

    public function getStopValue()
    {
        return $this->stopValue;
    }

    public function setStopValue($stopValue)
    {
        $this->stopValue = $stopValue;
        return $this;
    }

    public function getTransactionLines()
    {
        return $this->transactionLines;
    }

    public function addTransactionLine(FixedAssetTransactionLine $transactionLine)
    {
        $this->transactionLines[] = $transactionLine;
        return $this;
    }

    public function removeTransactionLine($index)
    {
        if (array_key_exists($index, $this->transactionLines)) {
            unset($this->transactionLines[$index]);
            return true;
        } else {
            return false;
        }
    }
}
