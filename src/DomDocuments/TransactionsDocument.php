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
            $transactionElement->setAttribute('raisewarning', Util::formatBoolean($transaction->getRaiseWarning()));
        }
        if ($transaction->getAutoBalanceVat() !== null) {
            $transactionElement->setAttribute('autobalancevat', Util::formatBoolean($transaction->getAutoBalanceVat()));
        }

        $this->rootElement->appendChild($transactionElement);

        // Header
        $headerElement = $this->createElement('header');
        $transactionElement->appendChild($headerElement);

        $headerElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($transaction->getOffice())));
        $headerElement->appendChild($this->createNodeWithTextContent('code', $transaction->getCode()));

        if ($transaction->getNumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('number', $transaction->getNumber()));
        }

        if ($transaction->getPeriod() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('period', $transaction->getPeriod()));
        }

        if ($transaction->getCurrency() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('currency', Util::objectToStr($transaction->getCurrency())));
        }

        if (Util::objectUses(RegimeField::class, $transaction) && $transaction->getRegime() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('regime', $transaction->getRegime()));
        }

        $headerElement->appendChild($this->createNodeWithTextContent("date", Util::formatDate($transaction->getDate()), $transaction, array('raisewarning' => 'getDateRaiseWarningToString')));

        if (Util::objectUses(StatementNumberField::class, $transaction) && $transaction->getStatementnumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('statementnumber', $transaction->getStatementnumber()));
        }

        if (Util::objectUses(CloseAndStartValueFields::class, $transaction)) {
            $headerElement->appendChild($this->createNodeWithTextContent('startvalue', Util::formatMoney($transaction->getStartValue())));
            $headerElement->appendChild($this->createNodeWithTextContent('closevalue', Util::formatMoney($transaction->getCloseValue())));
        }

        if (Util::objectUses(DueDateField::class, $transaction) && $transaction->getDueDate() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("duedate", Util::formatDate($transaction->getDueDate())));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction) && $transaction->getInvoiceNumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transaction->getInvoiceNumber(), $transaction, array('raisewarning' => 'getInvoiceNumberRaiseWarningToString')));
        }

        if (Util::objectUses(PaymentReferenceField::class, $transaction) && $transaction->getPaymentReference() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('paymentreference', $transaction->getPaymentReference()));
        }

        if ($transaction->getFreeText1() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext1", $transaction->getFreeText1()));
        }

        if ($transaction->getFreeText2() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext2", $transaction->getFreeText2()));
        }

        if ($transaction->getFreeText3() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext3", $transaction->getFreeText3()));
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

            $lineElement->appendChild($this->createNodeWithTextContent('dim1', Util::objectToStr($transactionLine->getDim1())));

            if (!empty($transactionLine->getDim2())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim2', Util::objectToStr($transactionLine->getDim2())));
            }

            if (!empty($transactionLine->getDim3())) {
                $lineElement->appendChild($this->createNodeWithTextContent('dim3', Util::objectToStr($transactionLine->getDim3())));
            }

            if (Util::objectUses(ValueFields::class, $transactionLine)) {
                $lineElement->appendChild($this->createNodeWithTextContent('debitcredit', $transactionLine->getDebitCredit()));
                $lineElement->appendChild($this->createNodeWithTextContent('value', Util::formatMoney($transactionLine->getValue())));
            }

            if (Util::objectUses(BaselineField::class, $transactionLine) && !empty($transactionLine->getBaseline())) {
                $lineElement->appendChild($this->createNodeWithTextContent('baseline', $transactionLine->getBaseline()));
            }
            
            if (!empty($transactionLine->getBaseValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('basevalue', Util::formatMoney($transactionLine->getBaseValue())));
            }
            
            if (!empty($transactionLine->getComment())) {
                $lineElement->appendChild($this->createNodeWithTextContent('comment', $transactionLine->getComment()));
            }
            
            if (Util::objectUses(CurrencyDateField::class, $transactionLine) && !empty($transactionLine->getCurrencyDate())) {
                $lineElement->appendChild($this->createNodeWithTextContent("currencydate", Util::formatDate($transactionLine->getCurrencyDate())));
            }

            if (!empty($transactionLine->getDescription())) {
                $lineElement->appendChild($this->createNodeWithTextContent('description', $transactionLine->getDescription()));
            }
            
            if (!empty($transactionLine->getDestOffice())) {
                $lineElement->appendChild($this->createNodeWithTextContent('destoffice', Util::objectToStr($transactionLine->getDestOffice())));
            }

            if (Util::objectUses(FreeCharField::class, $transactionLine) && !empty($transactionLine->getFreeChar())) {
                $lineElement->appendChild($this->createNodeWithTextContent('freechar', $transactionLine->getFreeChar()));
            }

            if (Util::objectUses(InvoiceNumberField::class, $transactionLine) && !empty($transactionLine->getInvoiceNumber())) {
                $lineElement->appendChild($this->createNodeWithTextContent('invoicenumber', $transactionLine->getInvoiceNumber()));
            }

            if (Util::objectUses(PerformanceCountryField::class, $transactionLine) && !empty($transactionLine->getPerformanceCountry())) {
                $lineElement->appendChild($this->createNodeWithTextContent('performancecountry', Util::objectToStr($transactionLine->getPerformanceCountry())));
            }

            if (Util::objectUses(PerformanceDateField::class, $transactionLine) && !empty($transactionLine->getPerformanceDate())) {
                $lineElement->appendChild($this->createNodeWithTextContent("performancedate", Util::formatDate($transactionLine->getPerformanceDate())));
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
                $lineElement->appendChild($this->createNodeWithTextContent('repvalue', Util::formatMoney($transactionLine->getRepValue())));
            }
            
            if (Util::objectUses(VatBaseTotalField::class, $transactionLine) && !empty($transactionLine->getVatBaseTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbasetotal', Util::formatMoney($transactionLine->getVatBaseTotal())));
            }
            
            if (!empty($transactionLine->getVatBaseTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbaseturnover', Util::formatMoney($transactionLine->getVatBaseTurnover())));
            }
            
            if (!empty($transactionLine->getVatBaseValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatbasevalue', Util::formatMoney($transactionLine->getVatBaseValue())));
            }

            if (!empty($transactionLine->getVatCode())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($transactionLine->getVatCode())));
            }
            
            if (Util::objectUses(VatRepTotalField::class, $transactionLine) && !empty($transactionLine->getVatRepTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatreptotal', Util::formatMoney($transactionLine->getVatRepTotal())));
            }
            
            if (!empty($transactionLine->getVatRepValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatrepvalue', Util::formatMoney($transactionLine->getVatRepValue())));
            }
            
            if (!empty($transactionLine->getVatRepTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatrepturnover', Util::formatMoney($transactionLine->getVatRepTurnover())));
            }

            if (Util::objectUses(VatTotalField::class, $transactionLine) && !empty($transactionLine->getVatTotal())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vattotal', Util::formatMoney($transactionLine->getVatTotal())));
            }
            
            if (!empty($transactionLine->getVatTurnover())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatturnover', Util::formatMoney($transactionLine->getVatTurnover())));
            }
            
            if (!empty($transactionLine->getVatValue())) {
                $lineElement->appendChild($this->createNodeWithTextContent('vatvalue', Util::formatMoney($transactionLine->getVatValue())));
            }
        }
    }
}
