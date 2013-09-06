<?php

namespace Pronamic\Twinfield\Transaction;

/**
 * Transaction class
 * 
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class Transaction
{

    private $destiny;
    private $office;
    private $code;
    private $date;
    private $dueDate;
    private $invoiceNumber;
    private $lines = array();

    /**
     * Add a TransactionLine to this Transaction
     * 
     * @param \Pronamic\Twinfield\Transaction\TransactionLine $line
     * @return \Pronamic\Twinfield\Transaction\Transaction
     */
    public function addLine(TransactionLine $line)
    {
        array_push($this->lines, $line);
        return $this;
    }

    public function getLines()
    {
        return $this->lines;
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

}
