<?php

namespace PhpTwinfield;

/**
 * Transaction class.
 *
 * For now only Sales and Purchase transactions are supported.
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 *
 * @todo Add $originReference
 * @todo Add $modificationDate
 * @todo Add $user
 * @todo Add $inputDate
 * @todo Add $paymentReference
 */
class Transaction extends BaseObject
{
    /**
     * Journal transaction will be saved as 'provisional'.
     * @const string
     */
    const DESTINY_TEMPORARY = 'temporary';

    /**
     * Journal transaction will be saved as 'final'.
     * @const string
     */
    const DESTINY_FINAL = 'final';

    /**
     * @var string Either self::DESTINY_TEMPORARY or self::DESTINY_FINAL.
     */
    private $destiny;

    /**
     * @var string Either 'true' or 'false'.
     */
    private $autoBalanceVat;

    /**
     * @var string Either 'true' or 'false'.
     */
    private $raiseWarning;

    /**
     * @var string The office code.
     */
    private $office;

    /**
     * @var string The transaction type code.
     */
    private $code;

    /**
     * @var int The transaction number.
     */
    private $number;

    /**
     * @var string Period in 'YYYY/PP' format (e.g. '2013/05').
     */
    private $period;

    /**
     * @var string The currency code.
     */
    private $currency;

    /**
     * @var string The date in 'YYYYMMDD' format.
     */
    private $date;

    /**
     * @var string The sales transaction origin.
     */
    private $origin;

    /**
     * @var string The due date in 'YYYYMMDD' format.
     */
    private $dueDate;

    /**
     * @var string The invoice number.
     */
    private $invoiceNumber;

    /**
     * @var string
     */
    private $freetext1;

    /**
     * @var string
     */
    private $freetext2;

    /**
     * @var string
     */
    private $freetext3;

    /**
     * @var TransactionLine[]
     */
    private $lines = array();

    /**
     * @var \PhpTwinfield\Customer
     * @deprecated Not used.
     */
    private $customer;

    /**
     * @return string Either self::DESTINY_TEMPORARY or self::DESTINY_FINAL.
     */
    public function getDestiny()
    {
        return $this->destiny;
    }

    /**
     * @param string $destiny Either self::DESTINY_TEMPORARY or self::DESTINY_FINAL.
     * @return $this
     */
    public function setDestiny($destiny)
    {
        $this->destiny = $destiny;

        return $this;
    }

    /**
     * Should VAT be rounded ('true') or not ('false')? Rounding will only be done with a maximum of two cents.
     *
     * @return string Either 'true' or 'false'.
     */
    public function getAutoBalanceVat()
    {
        return $this->autoBalanceVat;
    }

    /**
     * Should VAT be rounded ('true') or not ('false')? Rounding will only be done with a maximum of two cents.
     *
     * @param string $autoBalanceVat String 'true' or 'false'.
     * @return $this
     */
    public function setAutoBalanceVat($autoBalanceVat)
    {
        $this->autoBalanceVat = $autoBalanceVat;

        return $this;
    }

    /**
     * @return string Either 'true' or 'false'.
     */
    public function getRaiseWarning()
    {
        return $this->raiseWarning;
    }

    /**
     * Should warnings be given ('true') or not ('false')? Default 'true'.
     *
     * @param string $raiseWarning String 'true' or 'false'.
     * @return $this
     */
    public function setRaiseWarning($raiseWarning)
    {
        $this->raiseWarning = $raiseWarning;

        return $this;
    }

    /**
     * @return string The office code.
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param string $office The office code.
     * @return $this
     */
    public function setOffice($office)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * @return string The transaction type code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code The transaction type code.
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int The transaction number.
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * When creating a new sales transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a sales transaction, the related transaction number should be provided.
     *
     * @param int $number The transaction number.
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string Period in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left empty,
     *                the period is determined by the system based on the provided transaction date.
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param string $period Period in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left
     *                       empty, the period is determined by the system based on the provided transaction date.
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return string The currency code.
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency The currency code.
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string The date in 'YYYYMMDD' format.
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date The date in 'YYYYMMDD' format.
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string The sales transaction origin. Read-only attribute.
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin The sales transaction origin. Read-only attribute.
     * @return $this
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string The due date in 'YYYYMMDD' format.
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param string $dueDate The due date in 'YYYYMMDD' format.
     * @return $this
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * @return string The invoice number.
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber The invoice number.
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @param string $freetext1
     *
     * @return $this
     */
    public function setFreetext1($freetext1)
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @param string $freetext2
     *
     * @return $this
     */
    public function setFreetext2($freetext2)
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return string
     */
    public function getFreetext3()
    {
        return $this->freetext3;
    }

    /**
     * @param string $freetext3
     *
     * @return $this
     */
    public function setFreetext3($freetext3)
    {
        $this->freetext3 = $freetext3;

        return $this;
    }

    /**
     * @return TransactionLine[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Add a TransactionLine to this Transaction.
     *
     * @param TransactionLine $line
     *
     * @return Transaction
     */
    public function addLine(TransactionLine $line)
    {
        $this->lines[$line->getID()] = $line;

        return $this;
    }

    /**
     * @param string $index The ID of the line to remove.
     * @return bool False if the index doesn't exist.
     */
    public function removeLine($index)
    {
        if (array_key_exists($index, $this->lines)) {
            unset($this->lines[$index]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \PhpTwinfield\Customer
     * @deprecated Not used.
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \PhpTwinfield\Customer $customer
     * @return $this
     * @deprecated Not used.
     */
    public function setCustomer(\PhpTwinfield\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }
}
