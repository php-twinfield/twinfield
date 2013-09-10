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
    private $dim1Name;
    private $dim1ShortName;
    private $dim1Type;
    private $dim1InUse;
    private $dim1VatCode;
    private $dim1VatObligatory;
    
    private $dim2;
    private $dim2Name;
    private $dim2ShortName;
    private $dim2Type;
    private $dim2InUse;
    private $dim2VatCode;
    private $dim2VatObligatory;
    
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
    
    public function getDim1Name()
    {
        return $this->dim1Name;
    }

    public function setDim1Name($dim1Name)
    {
        $this->dim1Name = $dim1Name;
        return $this;
    }

    public function getDim1ShortName()
    {
        return $this->dim1ShortName;
    }

    public function setDim1ShortName($dim1ShortName)
    {
        $this->dim1ShortName = $dim1ShortName;
        return $this;
    }

    public function getDim1Type()
    {
        return $this->dim1Type;
    }

    public function setDim1Type($dim1Type)
    {
        $this->dim1Type = $dim1Type;
        return $this;
    }

    public function getDim1InUse()
    {
        return $this->dim1InUse;
    }

    public function setDim1InUse($dim1InUse)
    {
        $this->dim1InUse = $dim1InUse;
        return $this;
    }

    public function getDim1VatCode()
    {
        return $this->dim1VatCode;
    }

    public function setDim1VatCode($dim1VatCode)
    {
        $this->dim1VatCode = $dim1VatCode;
        return $this;
    }

    public function getDim1VatObligatory()
    {
        return $this->dim1VatObligatory;
    }

    public function setDim1VatObligatory($dim1VatObligatory)
    {
        $this->dim1VatObligatory = $dim1VatObligatory;
        return $this;
    }

    public function getDim2Name()
    {
        return $this->dim2Name;
    }

    public function setDim2Name($dim2Name)
    {
        $this->dim2Name = $dim2Name;
        return $this;
    }

    public function getDim2ShortName()
    {
        return $this->dim2ShortName;
    }

    public function setDim2ShortName($dim2ShortName)
    {
        $this->dim2ShortName = $dim2ShortName;
        return $this;
    }

    public function getDim2Type()
    {
        return $this->dim2Type;
    }

    public function setDim2Type($dim2Type)
    {
        $this->dim2Type = $dim2Type;
        return $this;
    }

    public function getDim2InUse()
    {
        return $this->dim2InUse;
    }

    public function setDim2InUse($dim2InUse)
    {
        $this->dim2InUse = $dim2InUse;
        return $this;
    }

    public function getDim2VatCode()
    {
        return $this->dim2VatCode;
    }

    public function setDim2VatCode($dim2VatCode)
    {
        $this->dim2VatCode = $dim2VatCode;
        return $this;
    }

    public function getDim2VatObligatory()
    {
        return $this->dim2VatObligatory;
    }

    public function setDim2VatObligatory($dim2VatObligatory)
    {
        $this->dim2VatObligatory = $dim2VatObligatory;
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
