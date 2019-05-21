<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Fields\DueDateField;
use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaselineField;
use PhpTwinfield\Fields\Transaction\TransactionLine\FreeCharField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueFields;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
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
            $transactionElement->setAttribute('raisewarning', $transaction->getRaiseWarningToString());
        }
        if ($transaction->getAutoBalanceVat() !== null) {
            $transactionElement->setAttribute('autobalancevat', $transaction->getAutoBalanceVatToString());
        }

        $this->rootElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $headerElement->appendChild($this->createNodeWithTextContent('office', $transaction->getOfficeToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('code', $transaction->getCode()));

        if ($transaction->getNumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('number', $transaction->getNumber()));
        }

        if ($transaction->getPeriod() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('period', $transaction->getPeriod()));
        }

        if ($transaction->getCurrency() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('currency', $transaction->getCurrencyToString()));
        }

        if ($transaction->getRegime() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('regime', $transaction->getRegime()));
        }

        $headerElement->appendChild($this->createNodeWithTextContent("date", $transaction->getDateToString()));

        if (Util::objectUses(StatementNumberField::class, $transaction) && $transaction->getStatementnumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('statementnumber', $transaction->getStatementnumber()));
        }

        if (Util::objectUses(CloseAndStartValueFields::class, $transaction)) {
            $headerElement->appendChild($this->createNodeWithTextContent('startvalue', $transaction->getStartValueToFloat()));
            $headerElement->appendChild($this->createNodeWithTextContent('closevalue', $transaction->getCloseValueToFloat()));
        }

        if (Util::objectUses(DueDateField::class, $transaction) && $transaction->getDueDate() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("duedate", $transaction->getDueDateToString()));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction) && $transaction->getInvoiceNumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transaction->getInvoiceNumber()));
        }

        if (Util::objectUses(PaymentReferenceField::class, $transaction) && $transaction->getPaymentReference() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('paymentreference', $transaction->getPaymentReference()));
        }

        if ($transaction->getFreetext1() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext1", $transaction->getFreetext1()));
        }

        if ($transaction->getFreetext2() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext2", $transaction->getFreetext2()));
        }

        if ($transaction->getFreetext3() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext3", $transaction->getFreetext3()));
        }

        $linesElement = $this->createElement('lines');
        $transactionElement->appendChild($linesElement);

        // Lines
        /** @var BaseTransactionLine $transactionLine */
        foreach ($transaction->getLines() as $transactionLine) {
            $lineElement = $this->createElement('line');
            $lineElement->setAttribute('type', $transactionLine->getLineType());
            $lineElement->setAttribute('id', $transactionLine->getId());
            $linesElement->appendChild($lineElement);

            $lineElement->appendChild($this->createNodeWithTextContent('dim1', $transactionLine->getDim1ToString()));

            if (!empty($transactionLine->getDim2ToString())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim2', $transactionLine->getDim2ToString()));
            }

            if (!empty($transactionLine->getDim3ToString())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim3', $transactionLine->getDim3ToString()));
            }

            if (Util::objectUses(ValueFields::class, $transactionLine)) {
                $lineElement->appendChild($this->createNodeWithTextContent('debitcredit', $transactionLine->getDebitCredit()));
                $lineElement->appendChild($this->createNodeWithTextContent('value', $transactionLine->getValueToFloat()));
            }

            if (Util::objectUses(BaselineField::class, $transactionLine) && !empty($transactionLine->getBaseline())) {
                $lineElement->appendChild($this->createNodeWithTextContent('baseline', $transactionLine->getBaseline()));
            }

            if (!empty($transactionLine->getDescription())) {
                $lineElement->appendChild($this->createNodeWithTextContent('description', $transactionLine->getDescription()));
            }

            if (Util::objectUses(FreeCharField::class, $transactionLine) && !empty($transactionLine->getFreeChar())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freechar', $freeChar));
            }

            if (Util::objectUses(FreeText1Field::class, $transactionLine) && !empty($transactionLine->getFreetext1())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freetext1', $transactionLine->getFreetext1()));
            }

            if (Util::objectUses(FreeText2Field::class, $transactionLine) && !empty($transactionLine->getFreetext2())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freetext2', $transactionLine->getFreetext2()));
            }

            if (Util::objectUses(FreeText3Field::class, $transactionLine) && !empty($transactionLine->getFreetext3())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freetext3', $transactionLine->getFreetext3()));
            }

            if (Util::objectUses(InvoiceNumberField::class, $transactionLine) && !empty($transactionLine->getInvoiceNumber())) {
                $lineElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transactionLine->getInvoiceNumber()));
            }

            if (Util::objectUses(PerformanceCountryField::class, $transactionLine) && !empty($transactionLine->getPerformanceCountryToString())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancecountry', $performanceCountry));
            }

            if (Util::objectUses(PerformanceDateField::class, $transactionLine) && !empty($transactionLine->getPerformanceDateToString())) {
                $lineElement->appendChild($this->createNodeWithTextContent("performancedate", $performanceDate));
            }

            if (Util::objectUses(PerformanceTypeField::class, $transactionLine) && !empty($transactionLine->getPerformanceType())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancetype', $performanceType));
            }

            if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine) && !empty($transactionLine->getPerformanceVatNumber())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancevatnumber', $performanceVatNumber));
            }

            if (Util::objectUses(VatTotalField::class, $transactionLine) && !empty($transactionLine->getVatTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vattotal', $transactionLine->getVatTotalToFloat()));
            }

            if (Util::objectUses(VatBaseTotalField::class, $transactionLine) && !empty($transactionLine->getVatBaseTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbasetotal', $transactionLine->getVatBaseTotalToFloat()));
            }

            if (!empty($transactionLine->getVatValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatvalue', $transactionLine->getVatValueToFloat()));
            }

            if ($transactionLine->getVatCode() !== null) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatcode', $transactionLine->getVatCodeToString()));
            }
        }
    }
}
