<?php

namespace Pronamic\Twinfield\Transaction;

use Pronamic\Twinfield\BaseObject;

/**
 * Transaction class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class Transaction extends BaseObject
{
    private $raiseWarning;
    private $destiny;
    private $office;
    private $code;
    private $currency;
    private $date;
    private $period;
    private $origin;

    /**
     * @var \Pronamic\Twinfield\Customer\Customer
     */
    private $customer;
    private $dueDate;
    private $invoiceNumber;
    private $number;
    private $freetext1;
    private $freetext2;
    private $freetext3;
    private $lines = array();

    /**
     * Add a TransactionLine to this Transaction.
     *
     * @param \Pronamic\Twinfield\Transaction\TransactionLine $line
     *
     * @return \Pronamic\Twinfield\Transaction\Transaction
     */
    public function addLine(TransactionLine $line)
    {
        $this->lines[$line->getID()] = $line;

        return $this;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function removeLine($index)
    {
        if (array_key_exists($index, $this->lines)) {
            unset($this->lines[$index]);

            return true;
        } else {
            return false;
        }
    }

    public function getDestiny()
    {
        return $this->destiny;
    }

    public function setDestiny($destiny)
    {
        $this->destiny = $destiny;

        return $this;
    }

    public function getOffice()
    {
        return $this->office;
    }

    public function setOffice($office)
    {
        $this->office = $office;

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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getRaiseWarning()
    {
        return $this->raiseWarning;
    }

    public function setRaiseWarning($raiseWarning)
    {
        $this->raiseWarning = $raiseWarning;

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

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer(\Pronamic\Twinfield\Customer\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

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

    /**
     * @return mixed
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @param mixed $freetext1
     *
     * @return Transaction
     */
    public function setFreetext1($freetext1)
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @param mixed $freetext2
     *
     * @return Transaction
     */
    public function setFreetext2($freetext2)
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreetext3()
    {
        return $this->freetext3;
    }

    /**
     * @param mixed $freetext3
     *
     * @return Transaction
     */
    public function setFreetext3($freetext3)
    {
        $this->freetext3 = $freetext3;

        return $this;
    }
}
