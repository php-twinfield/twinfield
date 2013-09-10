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
    private $vatCode;
    private $debitCredit;
    private $description;

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

}
