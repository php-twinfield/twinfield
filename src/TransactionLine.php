<?php

namespace PhpTwinfield;

/**
 * TransactionLine class.
 *
 * There are three types of transaction lines:
 * - Total line, with any relevant customer/supplier details.
 * - Detail lines, which are an analysis of the total line.
 * - VAT lines, with totals per VAT rate and the amounts on which the VAT was calculated.
 *
 * A minimum of two transaction lines must be provided per day book.
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 *
 * @todo Add $dim3
 * @todo Add $vatRepTotal
 * @todo Add $relation
 * @todo Add $repValueOpen
 * @todo Add $vatBaseValue
 * @todo Add $vatRepValue
 * @todo Add $vatTurnover
 * @todo Add $vatBaseTurnover
 * @todo Add $vatRepTurnover
 * @todo Add $baseline
 */
class TransactionLine
{
    const TYPE_TOTAL = 'total';
    const TYPE_DETAIL = 'detail';
    const TYPE_VAT = 'vat';

    const DEBIT = 'debit';
    const CREDIT = 'credit';

    const MATCHSTATUS_AVAILABLE = 'available';
    const MATCHSTATUS_MATCHED = 'matched';
    const MATCHSTATUS_PROPOSED = 'proposed';
    const MATCHSTATUS_NOTMATCHABLE = 'notmatchable';

    const PERFORMANCETYPE_SERVICES = 'services';
    const PERFORMANCETYPE_GOODS = 'goods';

    /**
     * @var string Either self::TYPE_TOTAL, self::TYPE_DETAIL or self::TYPE_VAT.
     */
    private $type;

    /**
     * @var string The line ID.
     */
    private $ID;

    /**
     * @var string
     */
    private $dim1;

    /**
     * @var string
     */
    private $dim2;

    /**
     * @var string Either self::DEBIT or self::CREDIT.
     */
    private $debitCredit;

    /**
     * @var float
     */
    private $value;

    /**
     * @var float Amount in the base currency.
     */
    private $baseValue;

    /**
     * @var float The exchange rate used for the calculation of the base amount.
     */
    private $rate;

    /**
     * @var float Amount in the reporting currency.
     */
    private $repValue;

    /**
     * @var float The exchange rate used for the calculation of the reporting amount.
     */
    private $repRate;

    /**
     * @var string Description of the transaction line. Max length is 40 characters.
     */
    private $description;

    /**
     * @var float Only if line type is total. The total VAT amount in the currency of the sales transaction.
     */
    private $vatTotal;

    /**
     * @var float Only if line type is total. The total VAT amount in base currency.
     */
    private $vatBaseTotal;

    /**
     * @var string Payment status of the sales transaction. One of the self::MATCHSTATUS_* constants.
     */
    private $matchStatus;

    /**
     * @var int Only if line type is total. The level of the matchable dimension.
     */
    private $matchLevel;

    /**
     * @var float Only if line type is total. The amount still owed in the currency of the sales transaction.
     */
    private $valueOpen;

    /**
     * @var float Only if line type is total. The amount still owed in base currency.
     */
    private $baseValueOpen;

    /**
     * @var string Only if line type is detail or vat. VAT code.
     */
    private $vatCode;

    /**
     * @var float Only if line type is detail. VAT amount in the currency of the sales transaction.
     */
    private $vatValue;

    /**
     * @var string Only if line type is detail or vat. Either self::PERFORMANCETYPE_SERVICES or
     *             self::PERFORMANCETYPE_GOODS.
     */
    private $performanceType;

    /**
     * @var string Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are
     *             used.
     */
    private $performanceCountry;

    /**
     * @var string Only if line type is detail or vat. Mandatory in case of an ICT VAT code.
     */
    private $performanceVatNumber;

    /**
     * @var string Only if line type is detail or vat. The performance date in 'YYYYMMDD' format.
     */
    private $performanceDate;

    /**
     * @var string Only if this is a journal transaction and type is detail. The invoice number, max 40 characters long.
     */
    private $invoiceNumber;

    /**
     * @var string Only if line type is total. The ID of the customer or supplier.
     */
    private $customerSupplier;

    public function __construct()
    {
        $this->ID = uniqid();
    }

    /**
     * @return string Either self::TYPE_TOTAL, self::TYPE_DETAIL or self::TYPE_VAT.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type Either self::TYPE_TOTAL, self::TYPE_DETAIL or self::TYPE_VAT.
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string The line ID.
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param string $ID The line ID.
     * @return $this
     */
    public function setID($ID)
    {
        $this->ID = $ID;

        return $this;
    }

    /**
     * @return string
     */
    public function getDim1()
    {
        return $this->dim1;
    }

    /**
     * If line type = total the accounts receivable balance account. When dim1 is omitted, by default the general ledger
     * account will be taken as entered at the customer in Twinfield.
     *
     * If line type = detail the profit and loss account.
     *
     * If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account
     * will be taken as entered at the VAT code in Twinfield.
     *
     * @param string $dim1
     * @return $this
     */
    public function setDim1($dim1)
    {
        $this->dim1 = $dim1;

        return $this;
    }

    /**
     * @return string
     */
    public function getDim2()
    {
        return $this->dim2;
    }

    /**
     * If line type = total the account receivable.
     *
     * If line type = detail the cost center or empty.
     *
     * If line type = vat empty.
     *
     * @param string $dim2
     * @return $this
     */
    public function setDim2($dim2)
    {
        $this->dim2 = $dim2;

        return $this;
    }

    /**
     * @return string Either self::DEBIT or self::CREDIT.
     */
    public function getDebitCredit()
    {
        return $this->debitCredit;
    }

    /**
     * If line type = total
     * - In case of a 'normal' sales transaction debit.
     * - In case of a credit sales transaction credit.
     *
     * If line type = detail or vat
     * - In case of a 'normal' sales transaction credit.
     * - In case of a credit sales transaction debit.
     *
     * @param string $debitCredit Either self::DEBIT or self::CREDIT.
     * @return $this
     */
    public function setDebitCredit($debitCredit)
    {
        $this->debitCredit = $debitCredit;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * If line type = total amount including VAT.
     * If line type = detail amount without VAT.
     * If line type = vat VAT amount.
     *
     * @param float $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = (float)$value;

        return $this;
    }

    /**
     * @return float Amount in the base currency.
     */
    public function getBaseValue()
    {
        return $this->baseValue;
    }

    /**
     * @param float $baseValue Amount in the base currency.
     * @return $this
     * @todo This field is currently read-only in this library.
     */
    public function setBaseValue($baseValue)
    {
        $this->baseValue = $baseValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     * @return $this
     * @todo This field is currently read-only in this library.
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return float
     */
    public function getRepValue()
    {
        return $this->repValue;
    }

    /**
     * @param float $repValue
     * @return $this
     * @todo This field is currently read-only in this library.
     */
    public function setRepValue($repValue)
    {
        $this->repValue = $repValue;

        return $this;
    }

    /**
     * @return float The exchange rate used for the calculation of the reporting amount.
     * @todo This field is currently read-only in this library.
     */
    public function getRepRate()
    {
        return $this->repRate;
    }

    /**
     * @param float $repRate The exchange rate used for the calculation of the reporting amount.
     * @return $this
     * @todo This field is currently read-only in this library.
     */
    public function setRepRate($repRate)
    {
        $this->repRate = $repRate;

        return $this;
    }

    /**
     * @return string Description of the transaction line. Max length is 40 characters.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description Description of the transaction line. Max length is 40 characters.
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return float Only if line type is total. The total VAT amount in the currency of the sales transaction.
     */
    public function getVatTotal()
    {
        return $this->vatTotal;
    }

    /**
     * @param float $vatTotal Only if line type is total. The total VAT amount in the currency of the sales transaction.
     * @return $this
     */
    public function setVatTotal($vatTotal)
    {
        $this->vatTotal = $vatTotal;

        return $this;
    }

    /**
     * @return float Only if line type is total. The total VAT amount in base currency.
     */
    public function getVatBaseTotal()
    {
        return $this->vatBaseTotal;
    }

    /**
     * @param float $vatBaseTotal Only if line type is total. The total VAT amount in base currency.
     * @return $this
     */
    public function setVatBaseTotal($vatBaseTotal)
    {
        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }

    /**
     * @return string Payment status of the sales transaction. One of the self::MATCHSTATUS_* constants.
     */
    public function getMatchStatus()
    {
        return $this->matchStatus;
    }

    /**
     * Payment status of the sales transaction.
     *
     * If line type is 'detail' or 'vat' this is always 'notmatchable'.
     *
     * Read-only attribute.
     *
     * @param string $matchStatus One of the self::MATCHSTATUS_* constants.
     * @return $this
     */
    public function setMatchStatus($matchStatus)
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    /**
     * @return int Only if line type is total. The level of the matchable dimension.
     */
    public function getMatchLevel()
    {
        return $this->matchLevel;
    }

    /**
     * Only if line type is total.
     *
     * Read-only attribute.
     *
     * @param int $matchLevel The level of the matchable dimension.
     * @return $this
     */
    public function setMatchLevel($matchLevel)
    {
        $this->matchLevel = $matchLevel;

        return $this;
    }

    /**
     * @return float Only if line type is total. The amount still owed in the currency of the sales transaction.
     */
    public function getValueOpen()
    {
        return $this->valueOpen;
    }

    /**
     * Only if line type is total.
     *
     * Read-only attribute.
     *
     * @param float $valueOpen The amount still owed in the currency of the sales transaction.
     * @return $this
     */
    public function setValueOpen($valueOpen)
    {
        $this->valueOpen = $valueOpen;

        return $this;
    }

    /**
     * @return float Only if line type is total. The amount still owed in base currency.
     */
    public function getBaseValueOpen()
    {
        return $this->baseValueOpen;
    }

    /**
     * Only if line type is total.
     *
     * Read-only attribute.
     *
     * @param float $baseValueOpen The amount still owed in base currency.
     *
     * @return $this
     */
    public function setBaseValueOpen($baseValueOpen)
    {
        $this->baseValueOpen = $baseValueOpen;

        return $this;
    }

    /**
     * @return string Only if line type is detail or vat. VAT code.
     */
    public function getVatCode()
    {
        return $this->vatCode;
    }

    /**
     * @param string $vatCode Only if line type is detail or vat. VAT code.
     * @return $this
     */
    public function setVatCode($vatCode)
    {
        $this->vatCode = $vatCode;

        return $this;
    }

    /**
     * @return float Only if line type is detail. VAT amount in the currency of the sales transaction.
     */
    public function getVatValue()
    {
        return $this->vatValue;
    }

    /**
     * @param float $vatValue Only if line type is detail. VAT amount in the currency of the sales transaction.
     * @return $this
     */
    public function setVatValue($vatValue)
    {
        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * @return string Only if line type is detail or vat. Either self::PERFORMANCETYPE_SERVICES or
     *                self::PERFORMANCETYPE_GOODS.
     */
    public function getPerformanceType()
    {
        return $this->performanceType;
    }

    /**
     * @param string $performanceType Only if line type is detail or vat. Either self::PERFORMANCETYPE_SERVICES or
     *                                self::PERFORMANCETYPE_GOODS. Mandatory in case of an ICT VAT code. The performance
     *                                type.
     * @return $this
     */
    public function setPerformanceType($performanceType)
    {
        $this->performanceType = $performanceType;

        return $this;
    }

    /**
     * @return string Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes
     *                are used.
     */
    public function getPerformanceCountry()
    {
        return $this->performanceCountry;
    }

    /**
     * Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not
     * added to the request, by default the country code of the customer will be taken.
     *
     * @param string $performanceCountry
     * @return $this
     */
    public function setPerformanceCountry($performanceCountry)
    {
        $this->performanceCountry = $performanceCountry;

        return $this;
    }

    /**
     * @return string Only if line type is detail or vat. Mandatory in case of an ICT VAT code.
     */
    public function getPerformanceVatNumber()
    {
        return $this->performanceVatNumber;
    }

    /**
     * If not added to the request, by default the VAT number of the customer will be taken.
     *
     * @param string $performanceVatNumber Only if line type is detail or vat. Mandatory in case of an ICT VAT code.
     * @return $this
     */
    public function setPerformanceVatNumber($performanceVatNumber)
    {
        $this->performanceVatNumber = $performanceVatNumber;

        return $this;
    }

    /**
     * @return string Only if line type is detail or vat. The performance date in 'YYYYMMDD' format.
     */
    public function getPerformanceDate()
    {
        return $this->performanceDate;
    }

    /**
     * @param string $performanceDate Only if line type is detail or vat. The performance date in 'YYYYMMDD' format.
     * @return $this
     */
    public function setPerformanceDate($performanceDate)
    {
        $this->performanceDate = $performanceDate;

        return $this;
    }

    /**
     * @return string Only if this is a journal transaction and type is detail. The invoice number, max 40 characters
     *                long.
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber Only if this is a journal transaction and type is detail. The invoice number, max 40
     *                              characters long.
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return string Only if line type is total. The ID of the customer or supplier.
     */
    public function getCustomerSupplier()
    {
        return $this->customerSupplier;
    }

    /**
     * @param string $customerSupplier Only if line type is total. The ID of the customer or supplier.
     * @return $this
     */
    public function setCustomerSupplier($customerSupplier)
    {
        $this->customerSupplier = $customerSupplier;

        return $this;
    }
}
