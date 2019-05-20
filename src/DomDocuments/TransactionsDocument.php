<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Fields\DueDateField;
use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\FreeCharField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueFields;
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
            $transactionElement->appendChild($this->createNodeWithTextContent('raisewarning', $transaction->getRaiseWarningToString()));
        }
        if ($transaction->isAutoBalanceVat() !== null) {
            $transactionElement->appendChild($this->createNodeWithTextContent('autobalancevat', $transaction->isAutoBalanceVatToString()));
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
            $currencyElement = $this->createNodeWithTextContent('currency', $transaction->getCurrencyToString());
            $headerElement->appendChild($currencyElement);
        }

        $dateElement = $this->createNodeWithTextContent("date", $transaction->getDateToString());
        $headerElement->appendChild($dateElement);

        if ($transaction->getPeriod() !== null) {
            $periodElement = $this->createNodeWithTextContent('period', $transaction->getPeriod());
            $headerElement->appendChild($periodElement);
        }

        if (in_array(InvoiceNumberField::class, class_uses($transaction)) && $transaction->getInvoiceNumber() !== null) {
            $invoiceNumberElement = $this->createNodeWithTextContent('invoicenumber', $transaction->getInvoiceNumber());
            $headerElement->appendChild($invoiceNumberElement);
        }

        if (in_array(PaymentReferenceField::class, class_uses($transaction)) && $transaction->getPaymentReference() !== null) {
            $paymentReferenceElement = $this->createNodeWithTextContent('paymentreference', $transaction->getPaymentReference());
            $headerElement->appendChild($paymentReferenceElement);
        }

        $officeElement = $this->createNodeWithTextContent('office', $transaction->getOfficeToString());
        $headerElement->appendChild($officeElement);

        if (Util::objectUses(DueDateField::class, $transaction) && $transaction->getDueDate() !== null) {
           $dueDateElement = $this->createNodeWithTextContent("duedate", $transaction->getDueDateToString());
            $headerElement->appendChild($dueDateElement);
        }

        if (Util::objectUses(StatementNumberField::class, $transaction) && $transaction->getStatementnumber() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent('statementnumber', $transaction->getStatementnumber()));
        }

        if ($transaction instanceof BankTransaction || $transaction instanceof CashTransaction) {
            $headerElement->appendChild($this->createNodeWithTextContent('startvalue', $transaction->getStartValueToFloat()));
            $headerElement->appendChild($this->createNodeWithTextContent('closevalue', $transaction->getCloseValueToFloat()));
        }

        if (Util::objectUses(FreeText1Field::class, $transaction)) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext1", $transaction->getFreetext1()));
        }

        if (Util::objectUses(FreeText2Field::class, $transaction) && $transaction->getFreetext2() !== null) {
            $headerElement->appendChild($this->createNodeWithTextContent("freetext2", $transaction->getFreetext2()));
        }

        if (Util::objectUses(FreeText3Field::class, $transaction)) {
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

            $dim1Element = $this->createNodeWithTextContent('dim1', $transactionLine->getDim1ToString());
            $lineElement->appendChild($dim1Element);

            $dim2 = $transactionLine->getDim2ToString();

            if (!empty($dim2)) {
                $dim2Element = $this->createNodeWithTextContent('dim2', $dim2);
                $lineElement->appendChild($dim2Element);
            }

            if (in_array(ValueOpenField::class, class_uses($transactionLine))) {
                $debitCreditElement = $this->createNodeWithTextContent('debitcredit', $transactionLine->getDebitCredit());
                $lineElement->appendChild($debitCreditElement);

                $valueElement = $this->createNodeWithTextContent('value', $transactionLine->getValueToFloat());
                $lineElement->appendChild($valueElement);
            }

            if (Util::objectUses(PerformanceTypeField::class, $transactionLine)) {
                $performanceType = $transactionLine->getPerformanceType();

                if (!empty($performanceType)) {
                    $perfElement = $this->createNodeWithTextContent('performancetype', $performanceType);
                    $lineElement->appendChild($perfElement);
                }
            }

            if (Util::objectUses(PerformanceCountryField::class, $transactionLine)) {
                $performanceCountry = $transactionLine->getPerformanceCountryToString();

                if (!empty($performanceCountry)) {
                    $perfCountryElement = $this->createNodeWithTextContent('performancecountry', $performanceCountry);
                    $lineElement->appendChild($perfCountryElement);
                }
            }

            if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine)) {
                $performanceVatNumber = $transactionLine->getPerformanceVatNumber();

                if (!empty($performanceVatNumber)) {
                    $perfVatNumberElement = $this->createNodeWithTextContent('performancevatnumber', $performanceVatNumber);
                    $lineElement->appendChild($perfVatNumberElement);
                }
            }

            if (Util::objectUses(PerformanceDateField::class, $transactionLine)) {
                $performanceDate = $transactionLine->getPerformanceDateToString();

                if (!empty($performanceDate)) {
                    $dueDateElement = $this->createNodeWithTextContent("performancedate", $performanceDate);
                    $lineElement->appendChild($dueDateElement);
                }
            }

            if (Util::objectUses(FreeCharField::class, $transactionLine)) {
                $freeChar = $transactionLine->getFreeChar();

                if (!empty($freeChar)) {
                    $freeCharElement = $this->createNodeWithTextContent('freechar', $freeChar);
                    $lineElement->appendChild($freeCharElement);
                }
            }

            if (Util::objectUses(FreeText1Field::class, $transactionLine)) {
                $freetext1 = $transactionLine->getFreetext1();

                if (!empty($freetext1)) {
                    $freetext1Element = $this->createNodeWithTextContent('freetext1', $freetext1);
                    $lineElement->appendChild($freetext1Element);
                }
            }

            if (Util::objectUses(FreeText2Field::class, $transactionLine)) {
                $freetext2 = $transactionLine->getFreetext2();

                if (!empty($freetext2)) {
                    $freetext2Element = $this->createNodeWithTextContent('freetext2', $freetext2);
                    $lineElement->appendChild($freetext2Element);
                }
            }

            if (Util::objectUses(FreeText3Field::class, $transactionLine)) {
                $freetext3 = $transactionLine->getFreetext3();

                if (!empty($freetext3)) {
                    $freetext3Element = $this->createNodeWithTextContent('freetext3', $freetext3);
                    $lineElement->appendChild($freetext3Element);
                }
            }

            if (Util::objectUses(VatTotalField::class, $transactionLine)) {
                $vatTotal = $transactionLine->getVatTotal();

                if (!empty($vatTotal)) {
                    $vatTotalElement = $this->createNodeWithTextContent('vattotal', $transactionLine->getVatTotalToFloat());
                    $lineElement->appendChild($vatTotalElement);
                }
            }

            if (Util::objectUses(VatBaseTotalField::class, $transactionLine)) {
                $vatBaseTotal= $transactionLine->getVatBaseTotal();

                if (!empty($vatBaseTotal)) {
                    $vatBaseTotalElement = $this->createNodeWithTextContent('vatbasetotal', $transactionLine->getVatBaseTotalToFloat());
                    $lineElement->appendChild($vatBaseTotalElement);
                }
            }

            if (Util::objectUses(InvoiceNumberField::class, $transactionLine) && $transactionLine->getInvoiceNumber() !== null) {
                $invoiceNumberElement = $this->createNodeWithTextContent('invoicenumber', $transactionLine->getInvoiceNumber());
                $lineElement->appendChild($invoiceNumberElement);
            }

            $vatValue = $transactionLine->getVatValue();

            if (!empty($vatValue)) {
                $vatElement = $this->createNodeWithTextContent('vatvalue', $transactionLine->getVatValueToFloat());
                $lineElement->appendChild($vatElement);
            }

            $baseline = $transactionLine->getBaseline();

            if (!empty($baseline)) {
                $baselineElement = $this->createNodeWithTextContent('baseline', $baseline);
                $lineElement->appendChild($baselineElement);
            }

            if ($transactionLine->getDescription() !== null) {
                $descriptionElement = $this->createNodeWithTextContent('description', $transactionLine->getDescription());
                $lineElement->appendChild($descriptionElement);
            }

            if (!LineType::TOTAL()->equals($transactionLine->getLineType()) && $transactionLine->getVatCode() !== null) {
                $vatCodeElement = $this->createNodeWithTextContent('vatcode', $transactionLine->getVatCodeToSring());
                $lineElement->appendChild($vatCodeElement);
            }
        }
    }
}
