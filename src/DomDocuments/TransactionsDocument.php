<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;

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
     * @param BaseTransaction $transaction
     */
    public function addTransaction(BaseTransaction $transaction)
    {
        // Transaction
        $transactionElement = $this->createElement('transaction');
        $transactionElement->setAttribute('destiny', $transaction->getDestiny());
        if ($transaction->getRaiseWarning() !== null) {
            $transactionElement->setAttribute('raisewarning', $transaction->getRaiseWarning() ? 'true' : 'false');
        }
        if ($transaction->getAutoBalanceVat() !== null) {
            $transactionElement->setAttribute('autobalancevat', $transaction->getAutoBalanceVat() ? 'true' : 'false');
        }

        $this->transactionsElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $codeElement = $this->createElement('code', $transaction->getCode());
        $headerElement->appendChild($codeElement);

        if ($transaction->getNumber() !== null) {
            $numberElement = $this->createElement('number', $transaction->getNumber());
            $headerElement->appendChild($numberElement);
        }

        if ($transaction->getCurrency() !== null) {
            $currencyElement = $this->createElement('currency', $transaction->getCurrency());
            $headerElement->appendChild($currencyElement);
        }

        $dateElement = $this->createElement('date', $transaction->getDate());
        $headerElement->appendChild($dateElement);

        if ($transaction->getPeriod() !== null) {
            $periodElement = $this->createElement('period', $transaction->getPeriod());
            $headerElement->appendChild($periodElement);
        }

        if (
            in_array(InvoiceNumberField::class, class_uses($transaction)) &&
            $transaction->getInvoiceNumber() !== null
        ) {
            $invoiceNumberElement = $this->createElement('invoicenumber', $transaction->getInvoiceNumber());
            $headerElement->appendChild($invoiceNumberElement);
        }

        if (
            in_array(PaymentReferenceField::class, class_uses($transaction)) &&
            $transaction->getPaymentReference() !== null
        ) {
            $paymentReferenceElement = $this->createElement('paymentreference', $transaction->getPaymentReference());
            $headerElement->appendChild($paymentReferenceElement);
        }

        $officeElement = $this->createElement('office', $transaction->getOffice());
        $headerElement->appendChild($officeElement);

        if (
            in_array(DueDateField::class, class_uses($transaction)) &&
            $transaction->getDueDate() !== null
        ) {
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

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        /** @var BaseTransactionLine $transactionLine */
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getType());
            $lineElement->setAttribute('id', $transactionLine->getId());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createElement('dim1', $transactionLine->getDim1());
            $lineElement->appendChild($dim1Element);

            $dim2 = $transactionLine->getDim2();
            if (!empty($dim2)) {
                $dim2Element = $this->createElement('dim2', $dim2);
                $lineElement->appendChild($dim2Element);
            }

            $value = $transactionLine->getValue();
            $value = number_format($value, 2, '.', '');
            $valueElement = $this->createElement('value', $value);
            $lineElement->appendChild($valueElement);

            $debitCreditElement = $this->createElement('debitcredit', $transactionLine->getDebitCredit());
            $lineElement->appendChild($debitCreditElement);

            if (in_array(PerformanceFields::class, class_uses($transactionLine))) {
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

                $performanceDate = $transactionLine->getPerformanceDate();
                if (!empty($performanceDate)) {
                    $perfDateElement = $this->createElement('performancedate', $performanceDate);
                    $lineElement->appendChild($perfDateElement);
                }
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createElement('vatvalue', $vatValue);
                $lineElement->appendChild($vatElement);
            }

            if (
                $transactionLine instanceof JournalTransactionLine &&
                $transactionLine->getType() == 'detail' &&
                $transactionLine->getInvoiceNumber() !== null
            ) {
                $invoiceNumberElement = $this->createElement('invoicenumber', $transactionLine->getInvoiceNumber());
                $lineElement->appendChild($invoiceNumberElement);
            }

            if ($transactionLine->getDescription() !== null) {
                $descriptionNode = $this->createTextNode($transactionLine->getDescription());
                $descriptionElement = $this->createElement('description');
                $descriptionElement->appendChild($descriptionNode);
                $lineElement->appendChild($descriptionElement);
            }

            if ($transactionLine->getType() != 'total' && $transactionLine->getVatCode() !== null) {
                $vatCodeElement = $this->createElement('vatcode', $transactionLine->getVatCode());
                $lineElement->appendChild($vatCodeElement);
            }
        }
    }
}
