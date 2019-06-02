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
use PhpTwinfield\Fields\InvoiceNumberField;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\CloseAndStartValueFields;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\Fields\Transaction\RegimeField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaselineField;
use PhpTwinfield\Fields\Transaction\TransactionLine\CurrencyDateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\FreeCharField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueFields;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
use PhpTwinfield\Util;

/**
 * TransactionsDocument class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
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

        if (Util::objectUses(RegimeField::class, $transaction) && $transaction->getRegime() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('regime', $transaction->getRegime()));
        }

        $headerElement->appendChild($this->createNodeWithTextContent("date", $transaction->getDateToString(), $transaction, array('raisewarning' => 'getDateRaiseWarningToString')));

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
            $headerElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transaction->getInvoiceNumber(), $transaction, array('raisewarning' => 'getInvoiceNumberRaiseWarningToString')));
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

            if (!empty($transactionLine->getDim2())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim2', $transactionLine->getDim2ToString()));
            }

            if (!empty($transactionLine->getDim3())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim3', $transactionLine->getDim3ToString()));
            }

            if (Util::objectUses(ValueFields::class, $transactionLine)) {
                $lineElement->appendChild($this->createNodeWithTextContent('debitcredit', $transactionLine->getDebitCredit()));
                $lineElement->appendChild($this->createNodeWithTextContent('value', $transactionLine->getValueToFloat()));
            }

            if (Util::objectUses(BaselineField::class, $transactionLine) && !empty($transactionLine->getBaseline())) {
                $lineElement->appendChild($this->createNodeWithTextContent('baseline', $transactionLine->getBaseline()));
            }
            
            if (!empty($transactionLine->getBaseValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('basevalue', $transactionLine->getBaseValueToFloat()));
            }
            
            if (!empty($transactionLine->getComment())) {
                $lineElement->appendChild($this->createNodeWithTextContent('comment', $transactionLine->getComment()));
            }
            
            if (Util::objectUses(CurrencyDateField::class, $transactionLine) && !empty($transactionLine->getCurrencyDate())) {
                $lineElement->appendChild($this->createNodeWithTextContent("currencydate", $transactionLine->getCurrencyDateToString()));
            }

            if (!empty($transactionLine->getDescription())) {
                $lineElement->appendChild($this->createNodeWithTextContent('description', $transactionLine->getDescription()));
            }
            
            if (!empty($transactionLine->getDestOffice())) {
                $lineElement->appendChild($this->createNodeWithTextContent('destoffice', $transactionLine->getDestOfficeToString()));
            }

            if (Util::objectUses(FreeCharField::class, $transactionLine) && !empty($transactionLine->getFreeChar())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freechar', $transactionLine->getFreeChar()));
            }

            if (Util::objectUses(InvoiceNumberField::class, $transactionLine) && !empty($transactionLine->getInvoiceNumber())) {
                $lineElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transactionLine->getInvoiceNumber()));
            }

            if (Util::objectUses(PerformanceCountryField::class, $transactionLine) && !empty($transactionLine->getPerformanceCountry())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancecountry', $transactionLine->getPerformanceCountryToString()));
            }

            if (Util::objectUses(PerformanceDateField::class, $transactionLine) && !empty($transactionLine->getPerformanceDate())) {
                $lineElement->appendChild($this->createNodeWithTextContent("performancedate", $transactionLine->getPerformanceDateToString()));
            }

            if (Util::objectUses(PerformanceTypeField::class, $transactionLine) && !empty($transactionLine->getPerformanceType())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancetype', $transactionLine->getPerformanceType()));
            }

            if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine) && !empty($transactionLine->getPerformanceVatNumber())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancevatnumber', $transactionLine->getPerformanceVatNumber()));
            }
            
            if (!empty($transactionLine->getRate())) {
                $lineElement->appendChild($this->createNodeWithTextContent('rate', $transactionLine->getRate()));
            }
            
            if (!empty($transactionLine->getRepRate())) {
                $lineElement->appendChild($this->createNodeWithTextContent('reprate', $transactionLine->getRepRate()));
            }
            
            if (!empty($transactionLine->getRepValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('repvalue', $transactionLine->getRepValueToFloat()));
            }
            
            if (Util::objectUses(VatBaseTotalField::class, $transactionLine) && !empty($transactionLine->getVatBaseTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbasetotal', $transactionLine->getVatBaseTotalToFloat()));
            }
            
            if (!empty($transactionLine->getVatBaseTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbaseturnover', $transactionLine->getVatBaseTurnoverToFloat()));
            }
            
            if (!empty($transactionLine->getVatBaseValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbasevalue', $transactionLine->getVatBaseValueToFloat()));
            }

            if (!empty($transactionLine->getVatCode())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatcode', $transactionLine->getVatCodeToString()));
            }
            
            if (Util::objectUses(VatRepTotalField::class, $transactionLine) && !empty($transactionLine->getVatRepTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatreptotal', $transactionLine->getVatRepTotalToFloat()));
            }
            
            if (!empty($transactionLine->getVatRepValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatrepvalue', $transactionLine->getVatRepValueToFloat()));
            }
            
            if (!empty($transactionLine->getVatRepTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatrepturnover', $transactionLine->getVatRepTurnoverToFloat()));
            }

            if (Util::objectUses(VatTotalField::class, $transactionLine) && !empty($transactionLine->getVatTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vattotal', $transactionLine->getVatTotalToFloat()));
            }
            
            if (!empty($transactionLine->getVatTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatturnover', $transactionLine->getVatTurnoverToFloat()));
            }
            
            if (!empty($transactionLine->getVatValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatvalue', $transactionLine->getVatValueToFloat()));
            }
        }
    }
}
