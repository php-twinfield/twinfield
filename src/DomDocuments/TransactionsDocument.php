<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionLineFields\FreeCharField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use PhpTwinfield\Util;

/**
 * TransactionsDocument class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionsDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "transactions";
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
            $transactionElement->appendChild($this->createBooleanAttribute('raisewarning', $transaction->getRaiseWarning()));
        }
        if ($transaction->isAutoBalanceVat() !== null) {
            $transactionElement->appendChild($this->createBooleanAttribute('autobalancevat', $transaction->isAutoBalanceVat()));
        }

        $this->rootElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $codeElement = $this->createNodeWithTextContent('code', $transaction->getCode());
        $headerElement->appendChild($codeElement);

        if ($transaction->getNumber() !== null) {
            $numberElement = $this->createNodeWithTextContent('number', $transaction->getNumber());
            $headerElement->appendChild($numberElement);
        }

        if ($transaction->getCurrency() !== null) {
            $currencyElement = $this->createNodeWithTextContent('currency', $transaction->getCurrency());
            $headerElement->appendChild($currencyElement);
        }

        $this->appendDateElement($headerElement, "date", $transaction->getDate());

        if ($transaction->getPeriod() !== null) {
            $periodElement = $this->createNodeWithTextContent('period', $transaction->getPeriod());
            $headerElement->appendChild($periodElement);
        }

        if (
            in_array(InvoiceNumberField::class, class_uses($transaction)) &&
            $transaction->getInvoiceNumber() !== null
        ) {
            $invoiceNumberElement = $this->createNodeWithTextContent('invoicenumber', $transaction->getInvoiceNumber());
            $headerElement->appendChild($invoiceNumberElement);
        }

        if (
            in_array(PaymentReferenceField::class, class_uses($transaction)) &&
            $transaction->getPaymentReference() !== null
        ) {
            $paymentReferenceElement = $this->createNodeWithTextContent('paymentreference', $transaction->getPaymentReference());
            $headerElement->appendChild($paymentReferenceElement);
        }

        $officeElement = $this->createNodeWithTextContent('office', $transaction->getOffice());
        $headerElement->appendChild($officeElement);

        if (Util::objectUses(DueDateField::class, $transaction) &&
            $transaction->getDueDate() !== null
        ) {
            $this->appendDateElement($headerElement, "duedate", $transaction->getDueDate());
        }

        $this->appendFreeTextFields($headerElement, $transaction);

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        /** @var BaseTransactionLine $transactionLine */
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getLineType());
            $lineElement->setAttribute('id', $transactionLine->getId());
            $linesElement->appendChild($lineElement);

            $dim1Element = $this->createNodeWithTextContent('dim1', $transactionLine->getDim1());
            $lineElement->appendChild($dim1Element);

            $dim2 = $transactionLine->getDim2();
            if (!empty($dim2)) {
                $dim2Element = $this->createNodeWithTextContent('dim2', $dim2);
                $lineElement->appendChild($dim2Element);
            }

            $this->appendValueValues($lineElement, $transactionLine);

            if (Util::objectUses(PerformanceFields::class, $transactionLine)) {
                /** @var PerformanceFields $transactionLine */
                $performanceType = $transactionLine->getPerformanceType();
                if (!empty($performanceType)) {
                    $perfElement = $this->createNodeWithTextContent('performancetype', $performanceType);
                    $lineElement->appendChild($perfElement);
                }

                $performanceCountry = $transactionLine->getPerformanceCountry();
                if (!empty($performanceCountry)) {
                    $perfCountryElement = $this->createNodeWithTextContent('performancecountry', $performanceCountry);
                    $lineElement->appendChild($perfCountryElement);
                }

                $performanceVatNumber = $transactionLine->getPerformanceVatNumber();
                if (!empty($performanceVatNumber)) {
                    $perfVatNumberElement = $this->createNodeWithTextContent('performancevatnumber', $performanceVatNumber);
                    $lineElement->appendChild($perfVatNumberElement);
                }

                $performanceDate = $transactionLine->getPerformanceDate();
                if (!empty($performanceDate)) {
                    $this->appendDateElement($lineElement, "performancedate", $transactionLine->getPerformanceDate());
                }
            }

            if (Util::objectUses(FreeCharField::class, $transactionLine)) {
                /** @var FreeCharField $transactionLine */
                $freeChar = $transactionLine->getFreeChar();
                if (!empty($freeChar)) {
                    $freeCharElement = $this->createNodeWithTextContent('freechar', $freeChar);
                    $lineElement->appendChild($freeCharElement);
                }
            }

            if (Util::objectUses(VatTotalFields::class, $transactionLine)) {
                /** @var VatTotalFields $transactionLine */
                $vatTotal = $transactionLine->getVatTotal();
                if (!empty($vatTotal)) {
                    $vatTotalElement = $this->createNodeWithTextContent('vattotal', Util::formatMoney($vatTotal));
                    $lineElement->appendChild($vatTotalElement);
                }

                $vatBaseTotal= $transactionLine->getVatBaseTotal();
                if (!empty($vatBaseTotal)) {
                    $vatBaseTotalElement = $this->createNodeWithTextContent('vatbasetotal', Util::formatMoney($vatBaseTotal));
                    $lineElement->appendChild($vatBaseTotalElement);
                }
            }

            $vatValue = $transactionLine->getVatValue();
            if (!empty($vatValue)) {
                $vatElement = $this->createNodeWithTextContent('vatvalue', Util::formatMoney($vatValue));
                $lineElement->appendChild($vatElement);
            }

            $baseline = $transactionLine->getBaseline();
            if (!empty($baseline)) {
                $baselineElement = $this->createNodeWithTextContent('baseline', $baseline);
                $lineElement->appendChild($baselineElement);
            }

            if (
                $transactionLine instanceof JournalTransactionLine &&
                LineType::DETAIL()->equals($transactionLine->getLineType()) &&
                $transactionLine->getInvoiceNumber() !== null
            ) {
                $invoiceNumberElement = $this->createNodeWithTextContent('invoicenumber', $transactionLine->getInvoiceNumber());
                $lineElement->appendChild($invoiceNumberElement);
            }

            if ($transactionLine->getDescription() !== null) {
                $descriptionNode = $this->createTextNode($transactionLine->getDescription());
                $descriptionElement = $this->createElement('description');
                $descriptionElement->appendChild($descriptionNode);
                $lineElement->appendChild($descriptionElement);
            }

            if (!LineType::TOTAL()->equals($transactionLine->getLineType()) && $transactionLine->getVatCode() !== null) {
                $vatCodeElement = $this->createNodeWithTextContent('vatcode', $transactionLine->getVatCode());
                $lineElement->appendChild($vatCodeElement);
            }
        }
    }
}
