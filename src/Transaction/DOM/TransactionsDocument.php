<?php

namespace PhpTwinfield\Transaction\DOM;

use \PhpTwinfield\Transaction\Transaction;
use \PhpTwinfield\Transaction\TransactionLine;

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
     * @param \PhpTwinfield\Transaction\Transaction $transaction
     */
    public function addTransaction(Transaction $transaction)
    {
        // Transaction
        $transactionElement = $this->createElement('transaction');
        $transactionElement->setAttribute('destiny', $transaction->getDestiny());
        if ($transaction->getRaiseWarning() !== null) {
            $transactionElement->setAttribute('raisewarning', $transaction->getRaiseWarning());
        }
        if ($transaction->getAutoBalanceVat() !== null) {
            $transactionElement->setAttribute('autobalancevat', $transaction->getAutoBalanceVat());
        }


        $this->transactionsElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $officeElement = $this->createElement('office', $transaction->getOffice());
        $codeElement = $this->createElement('code', $transaction->getCode());
        $dateElement = $this->createElement('date', $transaction->getDate());
        $periodElement = $this->createElement('period', $transaction->getPeriod());

        if ($transaction->getNumber() !== null) {
            $numberElement = $this->createElement('number', $transaction->getNumber());
            $headerElement->appendChild($numberElement);
        }

        if ($transaction->getCurrency() !== null) {
            $currencyElement = $this->createElement('currency', $transaction->getCurrency());
            $headerElement->appendChild($currencyElement);
        }

        if ($transaction->getInvoiceNumber() !== null) {
            $invoiceNumberElement = $this->createElement('invoicenumber', $transaction->getInvoiceNumber());
            $headerElement->appendChild($invoiceNumberElement);
        }

        if ($transaction->getDueDate() !== null) {
            $dueDateElement = $this->createElement('duedate', $transaction->getDueDate());
            $headerElement->appendChild($dueDateElement);
        }

        if ($transaction->getFreetext1() !== null) {
            $freetext1Element = $this->createElement('freetext1', $transaction->getFreetext1());
            $headerElement->appendChild($freetext1Element);
        }

        if ($transaction->getFreetext2() !== null) {
            $freetext2Element = $this->createElement('freetext2', $transaction->getFreetext2());
            $headerElement->appendChild($freetext2Element);
        }

        if ($transaction->getFreetext3() !== null) {
            $freetext3Element = $this->createElement('freetext3', $transaction->getFreetext3());
            $headerElement->appendChild($freetext3Element);
        }

        $headerElement->appendChild($officeElement);
        $headerElement->appendChild($codeElement);
        $headerElement->appendChild($dateElement);
        $headerElement->appendChild($periodElement);

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        /** @var TransactionLine $transactionLine */
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $lineElement->setAttribute('id', $transactionLine->getID());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $dim2Element = $this->createElement('dim2', $transactionLine->getDim2());
            $debitCreditElement = $this->createElement('debitcredit', $transactionLine->getDebitCredit());
            $value = $transactionLine->getValue();
            $value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);

            if ($transactionLine->getType() != 'total' && $transactionLine->getVatCode() !== null) {
                $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
            }

            $descriptionNode = $this->createTextNode($transactionLine->getDescription());
            $descriptionElement = $this->createElement('description');
            $descriptionElement->appendChild($descriptionNode);

            $lineElement->appendChild($dim1Element);
            $lineElement->appendChild($dim2Element);
            $lineElement->appendChild($valueElement);
            $lineElement->appendChild($debitCreditElement);

            $performanceType = $transactionLine->getPerformanceType();
            if (!empty($performanceType)) {
                $perfElement = $this->createElement('performancetype', $performanceType);
                $lineElement->appendChild($perfElement);
            }

            $performanceCountry = $transactionLine->getPerformanceCountry();
            if (!empty($performanceCountry)) {
                $perfCountryElement = $this->createElement('performancecountry', $performanceCountry);
                $lineElement->appendChild($perfCountryElement);
            }

            $performanceVatNumber = $transactionLine->getPerformanceVatNumber();
            if (!empty($performanceVatNumber)) {
                $perfVatNumberElement = $this->createElement('performancevatnumber', $performanceVatNumber);
                $lineElement->appendChild($perfVatNumberElement);
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createElement('vatvalue', $vatValue);
                $lineElement->appendChild($vatElement);
            }

            if ($transactionLine->getType() != 'total' && $transactionLine->getVatCode() !== null) {
                $lineElement->appendChild($vatCodeElement);
            }

            $lineElement->appendChild($descriptionElement);

            if ($transactionLine->getType() == 'detail' && $transactionLine->getInvoiceNumber() !== null) {
                $invoiceNumberElement = $this->createElement('invoicenumber', $transactionLine->getInvoiceNumber());
                $lineElement->appendChild($invoiceNumberElement);
            }

            $lineElement->appendChild($descriptionElement);
        }
    }
}
