<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;
use PhpTwinfield\Fields\DueDateField;
use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Response\Response;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Util;

class TransactionMapper extends BaseMapper
{
    /**
     * @param string   $transactionClassName
     * @param Response $response
     *
     * @return iterable|BaseTransaction[]
     * @throws Exception
     */
    public static function mapAll(string $transactionClassName, Response $response): iterable
    {
        foreach ($response->getResponseDocument()->getElementsByTagName('transaction') as $transactionElement) {
            yield self::map($transactionClassName, $transactionElement);
        }
    }

    public static function map(string $transactionClassName, Response $response): BaseTransaction
    {
        if (!is_a($transactionClassName, BaseTransaction::class, true)) {
            throw Exception::invalidTransactionClassName($transactionClassName);
        }

        $document = $response->getResponseDocument();
        $transactionElement = $document->documentElement;

        $transaction = new $transactionClassName();
        $transaction->setResult($transactionElement->getAttribute('result'));

        $autoBalanceVat = $transactionElement->getAttribute('autobalancevat');

        if (!empty($autoBalanceVat)) {
            $transaction->setAutoBalanceVat(self::parseBooleanAttribute($autoBalanceVat));
        }

        $destiny = $transactionElement->getAttribute('location');

        if (empty($destiny)) {
            $destiny = $transactionElement->getAttribute('destiny');
        }

        if (!empty($destiny)) {
            $transaction->setDestiny(self::parseEnumAttribute('Destiny', $destiny));
        }

        $raiseWarning = $transactionElement->getAttribute('raisewarning');

        if (!empty($raiseWarning)) {
            $transaction->setRaiseWarning(self::parseBooleanAttribute($raiseWarning));
        }

        $transaction->setCode(self::getField($transaction, $transactionElement, 'code'))
            ->setDateFromString(self::getField($transaction, $transactionElement, 'date'))
            ->setFreetext1(self::getField($transaction, $transactionElement, 'freetext1'))
            ->setFreetext2(self::getField($transaction, $transactionElement, 'freetext2'))
            ->setFreetext3(self::getField($transaction, $transactionElement, 'freetext3'))
            ->setOfficeFromString(self::getField($transaction, $transactionElement, 'office'))
            ->setOrigin(self::getField($transaction, $transactionElement, 'origin'))
            ->setPeriod(self::getField($transaction, $transactionElement, 'period'));

        $currency = self::getField($transaction, $transactionElement, 'currency');

        if (!empty($currency)) {
            $transaction->setCurrencyFromString($currency);
        }

        $number = self::getField($transaction, $transactionElement, 'number');

        if (!empty($number)) {
            $transaction->setNumber($number);
        }


        if (Util::objectUses(DueDateField::class, $transaction)) {
            $transaction->setDueDateFromString(self::getField($transaction, $transactionElement, 'duedate'));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transaction, $transactionElement, 'invoicenumber'));
        }

        if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
            $transaction->setPaymentReference(self::getField($transaction, $transactionElement, 'paymentreference'));
        }

        if (Util::objectUses(StatementNumberField::class, $transaction)) {
            $transaction->setStatementnumber(self::getField($transaction, $transactionElement, 'statementnumber'));
        }

        if ($transaction instanceof SalesTransaction) {
            $transaction->setOriginReference(self::getField($transaction, $transactionElement, 'originreference'));
        }

        if ($transaction instanceof JournalTransaction) {
            $transaction->setRegime(self::getField($transaction, $transactionElement, 'regime'));
        }

        if ($transaction instanceof BankTransaction || $transaction instanceof CashTransaction) {
            $transaction->setStartValueFromFloat(self::getField($transaction, $transactionElement, 'startvalue'));
        }

        // Parse the transaction lines
        $transactionLineClassName = $transaction->getLineClassName();

        $linesDOMTag = $transactionElement->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            $linesDOM = $linesDOMTag->item(0);

            foreach ($linesDOM->childNodes as $lineElement) {
                if ($lineElement->nodeType !== 1) {
                    continue;
                }

                self::checkForMessage($transaction, $lineElement);

                $transactionLine = new $transactionLineClassName();
                $lineType        = $lineElement->getAttribute('type');

                $transactionLine
                    ->setLineType(new LineType($lineType))
                    ->setId($lineElement->getAttribute('id'))
                    ->setDim1FromString(self::getField($transaction, $lineElement, 'dim1'))
                    ->setDim2FromString(self::getField($transaction, $lineElement, 'dim2'))
                    ->setValueFromFloat(self::getField($transaction, $lineElement, 'value'))
                    ->setDebitCredit(self::parseEnumAttribute('DebitCredit', self::getField($transaction, $lineElement, 'debitcredit')))
                    ->setBaseValueFromFloat(self::getField($transaction, $lineElement, 'basevalue'))
                    ->setRate(self::getField($transaction, $lineElement, 'rate'))
                    ->setRepValueFromFloat(self::getField($transaction, $lineElement, 'repvalue'))
                    ->setRepRate(self::getField($transaction, $lineElement, 'reprate'))
                    ->setDescription(self::getField($transaction, $lineElement, 'description'))
                    ->setMatchStatusFromString(self::getField($transaction, $lineElement, 'matchstatus'))
                    ->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'))
                    ->setVatCodeFromString(self::getField($transaction, $lineElement, 'vatcode'));

                $baseValueOpen = self::getField($transaction, $lineElement, 'basevalueopen') ?: self::getField($transaction, $lineElement, 'openbasevalue');

                if ($baseValueOpen) {
                    $transactionLine->setBaseValueOpenFromFloat($baseValueOpen);
                }

                $vatValue = self::getField($transaction, $lineElement, 'vatvalue');

                if ($lineType == LineType::DETAIL() && $vatValue) {
                    $transactionLine->setVatValueFromFloat($vatValue);
                }

                $baseline = self::getField($transaction, $lineElement, 'baseline');

                if ($baseline) {
                    $transactionLine->setBaseline($baseline);
                }

                if (Util::objectUses(FreeText1Field::class, $transactionLine)) {
                    $freetext1 = self::getField($transaction, $lineElement, 'freetext1');

                    if ($freetext1) {
                        $transactionLine->setFreetext1($freetext1);
                    }
                }

                if (Util::objectUses(FreeText2Field::class, $transactionLine)) {
                    $freetext2 = self::getField($transaction, $lineElement, 'freetext2');
                    if ($freetext2) {
                        $transactionLine->setFreetext2($freetext2);
                    }
                }

                if (Util::objectUses(FreeText3Field::class, $transactionLine)) {
                    $freetext3 = self::getField($transaction, $lineElement, 'freetext3');
                    if ($freetext3) {
                        $transactionLine->setFreetext3($freetext3);
                    }
                }

                if (Util::objectUses(PerformanceTypeField::class, $transactionLine)) {
                    $transactionLine->setPerformanceTypeFromString(self::getField($transaction, $lineElement, 'performancetype'));
                }

                 if (Util::objectUses(PerformanceCountryField::class, $transactionLine)) {
                    $transactionLine->setPerformanceCountryFromString(self::getField($transaction, $lineElement, 'performancecountry'));
                 }

                if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine)) {
                    $transactionLine->setPerformanceVatNumber(self::getField($transaction, $lineElement, 'performancevatnumber'));
                }

                 if (Util::objectUses(PerformanceDateField::class, $transactionLine)) {
                    $transactionLine->setPerformanceDateFromString(self::getField($transaction, $lineElement, 'performancedate'));
                }

                if (in_array(ValueOpenField::class, class_uses($transactionLine))) {
                    $valueOpen = self::getField($transaction, $lineElement, 'valueopen') ?: self::getField($transaction, $lineElement, 'openvalue');

                    if ($valueOpen) {
                        $transactionLine->setValueOpenFromFloat($valueOpen);
                    }
                }

                if (Util::objectUses(VatTotalField::class, $transactionLine)) {
                    $vatTotal = self::getField($transaction, $lineElement, 'vattotal');

                    if ($vatTotal) {
                        $transactionLine->setVatTotalFromFloat($vatTotal);
                    }
                }

                if (Util::objectUses(VatBaseTotalField::class, $transactionLine)) {
                    $vatBaseTotal = self::getField($transaction, $lineElement, 'vatbasetotal');

                    if ($vatBaseTotal) {
                        $transactionLine->setVatBaseTotalFromFloat($vatBaseTotal);
                    }
                }

                if (Util::objectUses(InvoiceNumberField::class, $transactionLine)) {
                    $transactionLine->setInvoiceNumber(self::getField($transaction, $lineElement, 'invoicenumber'));
                }

                $transaction->addLine($transactionLine);
            }
        }

        return $transaction;
    }
}