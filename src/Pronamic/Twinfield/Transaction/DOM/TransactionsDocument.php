<?php

namespace Pronamic\Twinfield\Transaction\DOM;

use Pronamic\Twinfield\Transaction\Transaction;

/**
 * TransactionsDocument class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionsDocument extends \DOMDocument
{
    /**
     * Holds the <transactions> element
     * that all additional elements should be a child of.
     *
     * @var \DOMElement
     */
    private $transactionsElement;

    /**
     * Creates the <transasctions> element and adds it to the property
     * transactionsElement.
     */
    public function __construct()
    {
        parent::__construct();

        $this->transactionsElement = $this->createElement('transactions');
        $this->appendChild($this->transactionsElement);
    }

    /**
     * Turns a passed Transaction class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the transaction
     * to this DOMDocument instance for submission usage.
     *
     * @param \Pronamic\Twinfield\Transaction\Transaction $transaction
     */
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
        $freetext1Element = $this->createElement('freetext1', $transaction->getFreetext1());
        $freetext2Element = $this->createElement('freetext2', $transaction->getFreetext2());
        $freetext3Element = $this->createElement('freetext3', $transaction->getFreetext3());

        $headerElement->appendChild($officeElement);
        $headerElement->appendChild($codeElement);
        $headerElement->appendChild($dateElement);
        $headerElement->appendChild($dueDateElement);
        $headerElement->appendChild($invoiceNumberElement);
        $headerElement->appendChild($freetext1Element);
        $headerElement->appendChild($freetext2Element);
        $headerElement->appendChild($freetext3Element);

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $lineElement->setAttribute('id', $transactionLine->getID());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $dim2Element = $this->createElement('dim2', $transactionLine->getDim2());
            $value = $transactionLine->getValue();
            $value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);

            if ($transactionLine->getType() != 'total') {
                $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
            }

            $descriptionNode = $this->createTextNode($transactionLine->getDescription());
            $descriptionElement = $this->createElement('description');
            $descriptionElement->appendChild($descriptionNode);

            $lineElement->appendChild($dim1Element);
            $lineElement->appendChild($dim2Element);
            $lineElement->appendChild($valueElement);

            $performanceType = $transactionLine->getPerformanceType();
            if (!empty($performanceType)) {
                $perfElement = $this->createElement('performancetype', $performanceType);
                $lineElement->appendChild($perfElement);
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createElement('vatvalue', $vatValue);
                $lineElement->appendChild($vatElement);
            }

            if ($transactionLine->getType() != 'total') {
                $lineElement->appendChild($vatCodeElement);
            }

            $lineElement->appendChild($descriptionElement);
        }
    }
}
