<?php

namespace Pronamic\Twinfield\Transaction\DOM;

use Pronamic\Twinfield\Transaction\Transaction;

/**
 * TransactionsDocument class
 * 
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionsDocument extends \DOMDocument
{
    private $transactionsElement;

    public function __construct()
    {
        parent::__construct();

        $this->transactionsElement = $this->createElement('transactions');
        $this->appendChild($this->transactionsElement);
    }

    public function addTransaction(Transaction $transaction)
    {
        // Transaction
        $transactionElement = $this->createElement('transaction');
        $transactionElement->setAttribute('destiny', $transaction->getDestiny());
        $this->transactionsElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $officeElement = $this->createElement('office', $transaction->getOffice());
        $codeElement = $this->createElement('code', $transaction->getCode());
        $dateElement = $this->createElement('date', $transaction->getDate());
        $dueDateElement = $this->createElement('duedate', $transaction->getDueDate());
        $invoiceNumberElement = $this->createElement('invoicenumber', $transaction->getInvoiceNumber());

        $headerElement->appendChild($officeElement);
        $headerElement->appendChild($codeElement);
        $headerElement->appendChild($dateElement);
        $headerElement->appendChild($dueDateElement);
        $headerElement->appendChild($invoiceNumberElement);

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        foreach ($transaction->getLines() as $transactionLine) {

            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $dim2Element = $this->createElement('dim2', $transactionLine->getDim2());
            $value = $transactionLine->getValue();
            $value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);
            
            if ($transactionLine->getType() != 'total') {
                $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
            }
            
            $descriptionElement = $this->createElement('description', $transactionLine->getDescription());

            $lineElement->appendChild($dim1Element);
            $lineElement->appendChild($dim2Element);
            $lineElement->appendChild($valueElement);
            
            if (!empty($transactionLine->getPerformanceType())) {
                $perfElement = $this->createElement('performancetype', $transactionLine->getPerformanceType());
                $lineElement->appendChild($perfElement);
            }
            
            if ($transactionLine->getType() != 'total') {
                $lineElement->appendChild($vatCodeElement);
            }
            
            $lineElement->appendChild($descriptionElement);
        }
    }
}
