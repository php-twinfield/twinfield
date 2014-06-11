<?php
namespace Pronamic\Twinfield\Transaction;

/**
 * TransactionLine class
 * 
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionLine
{
    private $type;
    
    private $dim1;    
    private $dim2;    
    private $value;
    private $debitCredit;
    private $description;
    private $vatCode;
    private $rate;
    private $baseValue;
    private $repRate;
    private $repValue;
    private $vatTotal;
    private $vatBaseTotal;
    private $matchLevel;
    private $customerSupplier;
    private $openValue;
    private $openBaseValue;
    private $matchStatus;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
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

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
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

    public function getDebitCredit()
    {
        return $this->debitCredit;
    }
    
    public function setDebitCredit($debitCredit)
    {
        $this->debitCredit = $debitCredit;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    public function getBaseValue()
    {
        return $this->baseValue;
    }

    public function setBaseValue($baseValue)
    {
        $this->baseValue = $baseValue;
        return $this;
    }

    public function getRepRate()
    {
        return $this->repRate;
    }

    public function setRepRate($repRate)
    {
        $this->repRate = $repRate;
        return $this;
    }

    public function getRepValue()
    {
        return $this->repValue;
    }

    public function setRepValue($repValue)
    {
        $this->repValue = $repValue;
        return $this;
    }

    public function getVatTotal()
    {
        return $this->vatTotal;
    }

    public function setVatTotal($vatTotal)
    {
        $this->vatTotal = $vatTotal;
        return $this;
    }

    public function getVatBaseTotal()
    {
        return $this->vatBaseTotal;
    }

    public function setVatBaseTotal($vatBaseTotal)
    {
        $this->vatBaseTotal = $vatBaseTotal;
        return $this;
    }

    public function getMatchLevel()
    {
        return $this->matchLevel;
    }

    public function setMatchLevel($matchLevel)
    {
        $this->matchLevel = $matchLevel;
        return $this;
    }

    public function getCustomerSupplier()
    {
        return $this->customerSupplier;
    }

    public function setCustomerSupplier($customerSupplier)
    {
        $this->customerSupplier = $customerSupplier;
        return $this;
    }

    public function getOpenValue()
    {
        return $this->openValue;
    }

    public function setOpenValue($openValue)
    {
        $this->openValue = $openValue;
        return $this;
    }

    public function getOpenBaseValue()
    {
        return $this->openBaseValue;
    }

    public function setOpenBaseValue($openBaseValue)
    {
        $this->openBaseValue = $openBaseValue;
        return $this;
    }

    public function getMatchStatus()
    {
        return $this->matchStatus;
    }

    public function setMatchStatus($matchStatus)
    {
        $this->matchStatus = $matchStatus;
        return $this;
    }
}
