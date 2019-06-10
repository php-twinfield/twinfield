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
use PhpTwinfield\Fields\InvoiceNumberField;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\CloseAndStartValueFields;
use PhpTwinfield\Fields\Transaction\OriginReferenceField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\Fields\Transaction\RegimeField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaselineField;
use PhpTwinfield\Fields\Transaction\TransactionLine\CurrencyDateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\MatchDateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\Message\Message;
use PhpTwinfield\PurchaseTransaction;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Response\Response;
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
            $transaction->setDestiny(self::parseEnumAttribute(\PhpTwinfield\Enums\Destiny::class, $destiny));
        }

        $raiseWarning = $transactionElement->getAttribute('raisewarning');

        if (!empty($raiseWarning)) {
            $transaction->setRaiseWarning(self::parseBooleanAttribute($raiseWarning));
        }

        $transaction->setCode(self::getField($transactionElement, 'code', $transaction))
            ->setCurrencyFromString(self::getField($transactionElement, 'currency', $transaction))
            ->setDateFromString(self::getField($transactionElement, 'date', $transaction))
            ->setFreetext1(self::getField($transactionElement, 'freetext1', $transaction))
            ->setFreetext2(self::getField($transactionElement, 'freetext2', $transaction))
            ->setFreetext3(self::getField($transactionElement, 'freetext3', $transaction))
            ->setOfficeFromString(self::getField($transactionElement, 'office', $transaction))
            ->setOrigin(self::getField($transactionElement, 'origin', $transaction))
            ->setNumber(self::getField($transactionElement, 'number', $transaction))
            ->setPeriod(self::getField($transactionElement, 'period', $transaction));

        if (Util::objectUses(CloseAndStartValueFields::class, $transaction)) {
            $transaction->setStartValueFromFloat(self::getField($transactionElement, 'startvalue', $transaction));
        }

        if (Util::objectUses(DueDateField::class, $transaction)) {
            $transaction->setDueDateFromString(self::getField($transactionElement, 'duedate', $transaction));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transactionElement, 'invoicenumber', $transaction));
            $transaction->setInvoiceNumberRaiseWarning(self::parseBooleanAttribute(self::getAttribute($transactionElement, 'invoicenumber', 'raisewarning')));
        }

        if (Util::objectUses(OriginReferenceField::class, $transaction)) {
            $transaction->setOriginReference(self::getField($transactionElement, 'originreference', $transaction));
        }

        if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
            $transaction->setPaymentReference(self::getField($transactionElement, 'paymentreference', $transaction));
        }

        if (Util::objectUses(RegimeField::class, $transaction)) {
            $transaction->setRegime(self::getField($transactionElement, 'regime', $transaction));
        }

        if (Util::objectUses(StatementNumberField::class, $transaction)) {
            $transaction->setStatementnumber(self::getField($transactionElement, 'statementnumber', $transaction));
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
                    ->setBaseValueFromFloat(self::getField($lineElement, 'basevalue', $transactionLine))
                    ->setComment(self::getField($lineElement, 'comment', $transactionLine))
                    ->setValueFromFloat(self::getField($lineElement, 'value', $transactionLine))
                    ->setDebitCredit(self::parseEnumAttribute(\PhpTwinfield\Enums\DebitCredit::class, self::getField($lineElement, 'debitcredit', $transactionLine)))
                    ->setDescription(self::getField($lineElement, 'description', $transactionLine))
                    ->setDestOfficeFromString(self::getField($lineElement, 'destoffice', $transactionLine))
                    ->setDim1FromString(self::getField($lineElement, 'dim1', $transactionLine))
                    ->setId($lineElement->getAttribute('id'))
                    ->setLineType(new LineType($lineType))
                    ->setMatchStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchStatus::class, self::getField($lineElement, 'matchstatus', $transactionLine)))
                    ->setRate(self::getField($lineElement, 'rate', $transactionLine))
                    ->setRepRate(self::getField($lineElement, 'reprate', $transactionLine))
                    ->setRepValueFromFloat(self::getField($lineElement, 'repvalue', $transactionLine));

                $freeChar = self::getField($lineElement, 'freechar', $transactionLine);

                if ($freeChar !== null && strlen($freeChar) == 1) {
                    $transactionLine->setFreeChar($freeChar);
                }

                if ($transaction instanceof BankTransaction || $transaction instanceof CashTransaction || $transaction instanceof JournalTransaction) {
                    if ($lineType == LineType::DETAIL()) {
                        $baseValueOpen = self::getField($lineElement, 'basevalueopen', $transactionLine) ?: self::getField($lineElement, 'openbasevalue', $transactionLine);

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpenFromFloat($baseValueOpen);
                        }

                        $transactionLine->setDim2FromString(self::getField($lineElement, 'dim2', $transactionLine));
                        $transactionLine->setDim3FromString(self::getField($lineElement, 'dim3', $transactionLine));
                        $transactionLine->setMatchLevel(self::getField($lineElement, 'matchlevel', $transactionLine));
                        $transactionLine->setRelation(self::getField($lineElement, 'relation', $transactionLine));
                        $transactionLine->setRepValueOpenFromFloat(self::getField($lineElement, 'repvalueopen', $transactionLine));
                    }
                }

                if ($transaction instanceof PurchaseTransaction || $transaction instanceof SalesTransaction) {
                    if ($lineType == LineType::DETAIL()) {
                        $transactionLine->setDim2FromString(self::getField($lineElement, 'dim2', $transactionLine));
                        $transactionLine->setDim3FromString(self::getField($lineElement, 'dim3', $transactionLine));
                    } elseif ($transaction instanceof PurchaseTransaction && $lineType == LineType::VAT()) {
                        $transactionLine->setDim3FromString(self::getField($lineElement, 'dim3', $transactionLine));
                    } elseif ($lineType == LineType::TOTAL()) {
                        $transactionLine->setDim2FromString(self::getField($lineElement, 'dim2', $transactionLine));

                        $baseValueOpen = self::getField($lineElement, 'basevalueopen', $transactionLine) ?: self::getField($lineElement, 'openbasevalue', $transactionLine);

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpenFromFloat($baseValueOpen);
                        }

                        $transactionLine->setMatchLevel(self::getField($lineElement, 'matchlevel', $transactionLine));
                        $transactionLine->setRelation(self::getField($lineElement, 'relation', $transactionLine));
                        $transactionLine->setRepValueOpenFromFloat(self::getField($lineElement, 'repvalueopen', $transactionLine));
                    }
                }

                if ($lineType == LineType::DETAIL()) {
                    if (Util::objectUses(CurrencyDateField::class, $transactionLine)) {
                        $transactionLine->setCurrencyDateFromString(self::getField($lineElement, 'currencydate', $transactionLine));
                    }

                    if (Util::objectUses(InvoiceNumberField::class, $transactionLine)) {
                        $transactionLine->setInvoiceNumber(self::getField($lineElement, 'invoicenumber', $transactionLine));
                    }

                    $transactionLine->setVatBaseValueFromFloat(self::getField($lineElement, 'vatbasevalue', $transactionLine));
                    $transactionLine->setVatRepValueFromFloat(self::getField($lineElement, 'vatrepvalue', $transactionLine));
                    $transactionLine->setVatValueFromFloat(self::getField($lineElement, 'vatvalue', $transactionLine));
                } elseif ($lineType == LineType::VAT()) {
                    if (Util::objectUses(BaselineField::class, $transactionLine)) {
                        $transactionLine->setBaseline(self::getField($lineElement, 'baseline', $transactionLine));
                    }

                    $transactionLine->setVatBaseTurnoverFromFloat(self::getField($lineElement, 'vatbaseturnover', $transactionLine));
                    $transactionLine->setVatRepTurnoverFromFloat(self::getField($lineElement, 'vatrepturnover', $transactionLine));
                    $transactionLine->setVatTurnoverFromFloat(self::getField($lineElement, 'vatturnover', $transactionLine));
                } elseif ($lineType == LineType::TOTAL()) {
                    if (Util::objectUses(MatchDateField::class, $transactionLine)) {
                        $transactionLine->setMatchDateFromString(self::getField($lineElement, 'matchdate', $transactionLine));
                    }

                    if (Util::objectUses(ValueOpenField::class, $transactionLine)) {
                        $valueOpen = self::getField($lineElement, 'valueopen', $transactionLine) ?: self::getField($lineElement, 'openvalue', $transactionLine);

                        if ($valueOpen) {
                            $transactionLine->setValueOpenFromFloat($valueOpen);
                        }
                    }

                    if (Util::objectUses(VatBaseTotalField::class, $transactionLine)) {
                        $transactionLine->setVatBaseTotalFromFloat(self::getField($lineElement, 'vatbasetotal', $transactionLine));
                    }

                    if (Util::objectUses(VatRepTotalField::class, $transactionLine)) {
                        $transactionLine->setVatRepTotalFromFloat(self::getField($lineElement, 'vatreptotal', $transactionLine));
                    }

                    if (Util::objectUses(VatTotalField::class, $transactionLine)) {
                        $transactionLine->setVatTotalFromFloat(self::getField($lineElement, 'vattotal', $transactionLine));
                    }
                }

                if ($lineType != LineType::TOTAL()) {
                    if (Util::objectUses(PerformanceCountryField::class, $transactionLine)) {
                        $transactionLine->setPerformanceCountryFromString(self::getField($lineElement, 'performancecountry', $transactionLine));
                    }

                    if (Util::objectUses(PerformanceDateField::class, $transactionLine)) {
                        $transactionLine->setPerformanceDateFromString(self::getField($lineElement, 'performancedate', $transactionLine));
                    }

                    if (Util::objectUses(PerformanceTypeField::class, $transactionLine)) {
                        $transactionLine->setPerformanceTypeFromString(self::getField($lineElement, 'performancetype', $transactionLine));
                    }

                    if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine)) {
                        $transactionLine->setPerformanceVatNumber(self::getField($lineElement, 'performancevatnumber', $transactionLine));
                    }

                    $transactionLine->setVatCodeFromString(self::getField($lineElement, 'vatcode', $transactionLine));
                }

                $transaction->addLine($transactionLine);
            }
        }

        return $transaction;
    }
}