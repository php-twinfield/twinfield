<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

class PurchaseInvoiceLine
{
    /**
     * @var float amount in the base currency
     */
    private $baseValue;
    /**
     * @var float only if the line type is 'total'. The amount still to be paid in base currency. Read-only attribute.
     */
    private $baseValueOpen;
    /**
     * @var string comment set on the transaction line
     */
    private $comment;
    /**
     * @var string not used in a purchase transaction. Use it only in case of bank or cash transactions.
     */
    private $currencyDate;
    /**
     * @var string if line type is 'total': in case of a normal purchase transaction 'credit', in case of a credit
     * purchase transaction, 'debit.
     * If line type is 'detail' or 'vat': in case of a 'normal' purchase transaction 'debit', in case of a credit
     * purchase transaction, 'credit'.
     */
    private $debitCredit;
    /**
     * @var string the description of the transaction line
     */
    private $description;
    /**
     * @var string only if line type is 'detail'. Office code. Used for inter company transactions - here you define in
     * which company the transaction line should be posted
     */
    private $destOffice;
    /**
     * @var string if line type is 'total', the accounts payable balance account, if line type is 'detail', the profit
     * and loss account, if line type is 'vat', the VAT balance account
     */
    private $dim1;
    /**
     * @var string if line type is 'total', the account payable, if line type is 'detail', the cost center or empty, if
     * line type is 'vat' the project or asset or empty
     */
    private $dim2;
    /**
     * @var string if line type is 'total', empty, if line type is 'detail', the project or asset or empty, if line type
     * is 'vat' the project or asset or empty
     */
    private $dim3;
    /**
     * @var string free character field. If line type is 'total' and filled with 'N' the purchase invoice is excluded
     * form payment runs done in Twinfield.
     */
    private $freeChar;
    /**
     * @var integer line identifier
     */
    private $id;
    /**
     * @var string only if the line type is 'total'. The date on which the purchase invoice is matched. Read-only
     * attribute.
     */
    private $matchDate;
    /**
     * @var integer only if the line type is 'total'. The level of the matchable dimension. Read-only attribute.
     */
    private $matchLevel;
    /**
     * @var string 'available', 'matched', 'proposed', or 'notmatchable'. Payment status of the purchase transaction. If
     * line type 'detail', or 'vat', always 'nonmatchable'. Read-only attribute.
     */
    private $matchStatus;
    /**
     * @var float the exchange rate used for the calculation of the base amount
     */
    private $rate;
    /**
     * @var integer only if line type is 'total'. Read-only attribute.
     */
    private $relation;
    /**
     * @var float the exchange rate used for the calculation of the reporting amount
     */
    private $repRate;
    /**
     * @var float the amount in the reporting currency
     */
    private $repValue;
    /**
     * @var float only if line type is 'total'. The amount still to be paid in reporting currency. Read-only attribute.
     */
    private $repValueOpen;
    /**
     * @var string line type
     */
    private $type;
    /**
     * @var float if line type is 'total', the amount including VAT, if line type = 'detail', the amount without VAT,
     * if line type is 'vat', the VAT amount
     */
    private $value;
    /**
     * @var float only if line type is 'total'. The amount still to be paid in the currency of the purchase
     * transaction. Read-only attribute.
     */
    private $valueOpen;
    /**
     * @var float only if the type is 'total'. The total VAT amount in base currency
     */
    private $vatBaseTotal;
    /**
     * @var float only if line type is 'vat'. Amount on which VAT was calculated in base currency.
     */
    private $vatBaseTurnover;
    /**
     * @var string only if line type is 'detail' or 'vat'. VAT code.
     */
    private $vatCode;
    /**
     * @var float only if the type is 'total'. The total VAT amount in reporting currency
     */
    private $vatRepTotal;
    /**
     * @var float only if the line type is 'detail'. VAT amount in reporting currency.
     */
    private $vatRepValue;
    /**
     * @var float only if line type is 'vat'. Amount on which VAT was calculated in reporting currency.
     */
    private $vatRepTurnover;
    /**
     * @var float only if the type is 'total'. The total VAT amount in the currency of the purchase transaction
     */
    private $vatTotal;
    /**
     * @var float only if line type is 'vat'. Amount on which VAT was calculated in the currency of the purchase
     * transaction
     */
    private $vatTurnover;

    /**
     * @return float
     */
    public function getBaseValue()
    {
        return $this->baseValue;
    }

    /**
     * @return float
     */
    public function getBaseValueOpen()
    {
        return $this->baseValueOpen;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCurrencyDate()
    {
        return $this->currencyDate;
    }

    /**
     * @return string
     */
    public function getDebitCredit()
    {
        return $this->debitCredit;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDestOffice()
    {
        return $this->destOffice;
    }

    /**
     * @return string
     */
    public function getDim1()
    {
        return $this->dim1;
    }

    /**
     * @return string
     */
    public function getDim2()
    {
        return $this->dim2;
    }

    /**
     * @return string
     */
    public function getDim3()
    {
        return $this->dim3;
    }

    /**
     * @return string
     */
    public function getFreeChar()
    {
        return $this->freeChar;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMatchDate()
    {
        return $this->matchDate;
    }

    /**
     * @return int
     */
    public function getMatchLevel()
    {
        return $this->matchLevel;
    }

    /**
     * @return string
     */
    public function getMatchStatus()
    {
        return $this->matchStatus;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return int
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @return float
     */
    public function getRepRate()
    {
        return $this->repRate;
    }

    /**
     * @return float
     */
    public function getRepValue()
    {
        return $this->repValue;
    }

    /**
     * @return float
     */
    public function getRepValueOpen()
    {
        return $this->repValueOpen;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getValueOpen()
    {
        return $this->valueOpen;
    }

    /**
     * @return float
     */
    public function getVatBaseTotal()
    {
        return $this->vatBaseTotal;
    }

    /**
     * @return float
     */
    public function getVatBaseTurnover()
    {
        return $this->vatBaseTurnover;
    }

    /**
     * @return string
     */
    public function getVatCode()
    {
        return $this->vatCode;
    }

    /**
     * @return float
     */
    public function getVatRepTotal()
    {
        return $this->vatRepTotal;
    }

    /**
     * @return float
     */
    public function getVatRepValue()
    {
        return $this->vatRepValue;
    }

    /**
     * @return float
     */
    public function getVatRepTurnover()
    {
        return $this->vatRepTurnover;
    }

    /**
     * @return float
     */
    public function getVatTotal()
    {
        return $this->vatTotal;
    }

    /**
     * @return float
     */
    public function getVatTurnover()
    {
        return $this->vatTurnover;
    }

    /**
     * @param float $value
     */
    public function setBaseValue($value)
    {
        $this->baseValue = $value;
    }

    /**
     * @param string $value
     */
    public function setComment($value)
    {
        $this->comment = $value;
    }

    /**
     * @param string $value
     */
    public function setDebitCredit($value)
    {
        $this->debitCredit = $value;
    }

    /**
     * @param string $value
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }

    /**
     * @param string $value
     */
    public function setDestOffice($value)
    {
        $this->destOffice = $value;
    }

    /**
     * @param string $value
     */
    public function setDim1($value)
    {
        $this->dim1 = $value;
    }

    /**
     * @param string $value
     */
    public function setDim2($value)
    {
        $this->dim2 = $value;
    }

    /**
     * @param string $value
     */
    public function setDim3($value)
    {
        $this->dim3 = $value;
    }

    /**
     * @param string $value
     */
    public function setFreeChar($value)
    {
        $this->freeChar = $value;
    }

    /**
     * @param integer $value
     */
    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * @param float $value
     */
    public function setRate($value)
    {
        $this->rate = $value;
    }

    /**
     * @param float $value
     */
    public function setRepRate($value)
    {
        $this->repRate = $value;
    }

    /**
     * @param float $value
     */
    public function setRepValue($value)
    {
        $this->repValue = $value;
    }

    /**
     * @param string $value
     */
    public function setType($value)
    {
        $this->type = $value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param float $value
     */
    public function setVatBaseTotal($value)
    {
        $this->vatBaseTotal = $value;
    }

    /**
     * @param float $value
     */
    public function setVatBaseTurnover($value)
    {
        $this->vatBaseTurnover = $value;
    }

    /**
     * @param string $value
     */
    public function setVatCode($value)
    {
        $this->vatCode = $value;
    }

    /**
     * @param float $value
     */
    public function setVatRepTotal($value)
    {
        $this->vatRepTotal = $value;
    }

    /**
     * @param float $value
     */
    public function setVatRepValue($value)
    {
        $this->vatRepValue = $value;
    }

    /**
     * @param float $value
     */
    public function setVatRepTurnover($value)
    {
        $this->vatRepTurnover = $value;
    }

    /**
     * @param float $value
     */
    public function setVatTotal($value)
    {
        $this->vatTotal = $value;
    }

    /**
     * @param float $value
     */
    public function setVatTurnover($value)
    {
        $this->vatTurnover = $value;
    }
}