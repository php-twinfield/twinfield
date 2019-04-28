<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetTransactionLine
{
    private $amount;
    private $code;
    private $dim1;
    private $dim2;
    private $dim3;
    private $dim4;
    private $dim5;
    private $dim6;
    private $line;
    private $number;
    private $period;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
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

    public function getDim1()
    {
        return $this->dim1;
    }

    public function setDim1($dim1)
    {
        $this->dim1 = $dim1;
        return $this;
    }

    public function getDim2()
    {
        return $this->dim2;
    }

    public function setDim2($dim2)
    {
        $this->dim2 = $dim2;
        return $this;
    }

    public function getDim3()
    {
        return $this->dim3;
    }

    public function setDim3($dim3)
    {
        $this->dim3 = $dim3;
        return $this;
    }

    public function getDim4()
    {
        return $this->dim4;
    }

    public function setDim4($dim4)
    {
        $this->dim4 = $dim4;
        return $this;
    }

    public function getDim5()
    {
        return $this->dim5;
    }

    public function setDim5($dim5)
    {
        $this->dim5 = $dim5;
        return $this;
    }

    public function getDim6()
    {
        return $this->dim6;
    }

    public function setDim6($dim6)
    {
        $this->dim6 = $dim6;
        return $this;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }
}
