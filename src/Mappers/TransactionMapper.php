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
use PhpTwinfield\Fields\Transaction\CloseAndStartValueFields;
use PhpTwinfield\Fields\Transaction\InvoiceNumberField;
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
            $transaction->setDestiny(self::parseEnumAttribute('Destiny', $destiny));
        }

        $raiseWarning = $transactionElement->getAttribute('raisewarning');

        if (!empty($raiseWarning)) {
            $transaction->setRaiseWarning(self::parseBooleanAttribute($raiseWarning));
        }

        $transaction->setCode(self::getField($transaction, $transactionElement, 'code'))
            ->setCurrencyFromString(self::getField($transaction, $transactionElement, 'currency'))
            ->setDateFromString(self::getField($transaction, $transactionElement, 'date'))
            ->setFreetext1(self::getField($transaction, $transactionElement, 'freetext1'))
            ->setFreetext2(self::getField($transaction, $transactionElement, 'freetext2'))
            ->setFreetext3(self::getField($transaction, $transactionElement, 'freetext3'))
            ->setOfficeFromString(self::getField($transaction, $transactionElement, 'office'))
            ->setOrigin(self::getField($transaction, $transactionElement, 'origin'))
            ->setNumber(self::getField($transaction, $transactionElement, 'number'))
            ->setPeriod(self::getField($transaction, $transactionElement, 'period'));

        if (Util::objectUses(CloseAndStartValueFields::class, $transaction)) {
            $transaction->setStartValueFromFloat(self::getField($transaction, $transactionElement, 'startvalue'));
        }

        if (Util::objectUses(DueDateField::class, $transaction)) {
            $transaction->setDueDateFromString(self::getField($transaction, $transactionElement, 'duedate'));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transaction, $transactionElement, 'invoicenumber'));
            $transaction->setInvoiceNumberRaiseWarning(self::parseBooleanAttribute(self::getAttribute($transactionElement, 'invoicenumber', 'raisewarning')));
        }

        if (Util::objectUses(OriginReferenceField::class, $transaction)) {
            $transaction->setOriginReference(self::getField($transaction, $transactionElement, 'originreference'));
        }

        if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
            $transaction->setPaymentReference(self::getField($transaction, $transactionElement, 'paymentreference'));
        }

        if (Util::objectUses(RegimeField::class, $transaction)) {
            $transaction->setRegime(self::getField($transaction, $transactionElement, 'regime'));
        }

        if (Util::objectUses(StatementNumberField::class, $transaction)) {
            $transaction->setStatementnumber(self::getField($transaction, $transactionElement, 'statementnumber'));
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
                    ->setBaseValueFromFloat(self::getField($transaction, $lineElement, 'basevalue'))
                    ->setComment(self::getField($transaction, $lineElement, 'comment'))
                    ->setDebitCredit(self::parseEnumAttribute('DebitCredit', self::getField($transaction, $lineElement, 'debitcredit')))
                    ->setDescription(self::getField($transaction, $lineElement, 'description'))
                    ->setDestOfficeFromString(self::getField($transaction, $lineElement, 'destoffice'))
                    ->setDim1FromString(self::getField($transaction, $lineElement, 'dim1'))
                    ->setFreeChar(self::getField($transaction, $lineElement, 'freechar'))
                    ->setId($lineElement->getAttribute('id'))
                    ->setLineType(new LineType($lineType))
                    ->setMatchStatusFromString(self::getField($transaction, $lineElement, 'matchstatus'))
                    ->setRate(self::getField($transaction, $lineElement, 'rate'))
                    ->setRepRate(self::getField($transaction, $lineElement, 'reprate'))
                    ->setRepValueFromFloat(self::getField($transaction, $lineElement, 'repvalue'))
                    ->setValueFromFloat(self::getField($transaction, $lineElement, 'value'));

                if ($transaction instanceof BankTransaction || $transaction instanceof CashTransaction || $transaction instanceof JournalTransaction) {
                    if ($lineType == LineType::DETAIL()) {
                        $baseValueOpen = self::getField($transaction, $lineElement, 'basevalueopen') ?: self::getField($transaction, $lineElement, 'openbasevalue');

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpenFromFloat($baseValueOpen);
                        }

                        $transactionLine->setDim2FromString(self::getField($transaction, $lineElement, 'dim2'));
                        $transactionLine->setDim3FromString(self::getField($transaction, $lineElement, 'dim3'));
                        $transactionLine->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'));
                        $transactionLine->setRelation(self::getField($transaction, $lineElement, 'relation'));
                        $transactionLine->setRepValueOpenFromFloat(self::getField($transaction, $lineElement, 'repvalueopen'));
                    }
                }

                if ($transaction instanceof PurchaseTransaction || $transaction instanceof SalesTransaction) {
                    if (!($transaction instanceof SalesTransaction && $lineType == LineType::VAT())) {
                        $transactionLine->setDim2FromString(self::getField($transaction, $lineElement, 'dim2'));
                        $transactionLine->setDim3FromString(self::getField($transaction, $lineElement, 'dim3'));
                    }

                    if ($lineType == LineType::TOTAL()) {
                        $baseValueOpen = self::getField($transaction, $lineElement, 'basevalueopen') ?: self::getField($transaction, $lineElement, 'openbasevalue');

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpenFromFloat($baseValueOpen);
                        }

                        $transactionLine->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'));
                        $transactionLine->setRelation(self::getField($transaction, $lineElement, 'relation'));
                        $transactionLine->setRepValueOpenFromFloat(self::getField($transaction, $lineElement, 'repvalueopen'));
                    }
                }

                if ($lineType == LineType::DETAIL()) {
                    if (Util::objectUses(CurrencyDateField::class, $transactionLine)) {
                        $transactionLine->setCurrencyDateFromString(self::getField($transaction, $lineElement, 'currencydate'));
                    }

                    if (Util::objectUses(InvoiceNumberField::class, $transactionLine)) {
                        $transactionLine->setInvoiceNumber(self::getField($transaction, $lineElement, 'invoicenumber'));
                    }

                    $transactionLine->setVatBaseValueFromFloat(self::getField($transaction, $lineElement, 'vatbasevalue'));
                    $transactionLine->setVatRepValueFromFloat(self::getField($transaction, $lineElement, 'vatrepvalue'));
                    $transactionLine->setVatValueFromFloat(self::getField($transaction, $lineElement, 'vatvalue'));
                }

                if ($lineType == LineType::VAT()) {
                    if (Util::objectUses(BaselineField::class, $transactionLine)) {
                        $transactionLine->setBaseline(self::getField($transaction, $lineElement, 'baseline'));
                    }

                    $transactionLine->setVatBaseTurnoverFromFloat(self::getField($transaction, $lineElement, 'vatbaseturnover'));
                    $transactionLine->setVatRepTurnoverFromFloat(self::getField($transaction, $lineElement, 'vatrepturnover'));
                    $transactionLine->setVatTurnoverFromFloat(self::getField($transaction, $lineElement, 'vatturnover'));
                }

                if ($lineType != LineType::TOTAL()) {
                    if (Util::objectUses(PerformanceCountryField::class, $transactionLine)) {
                        $transactionLine->setPerformanceCountryFromString(self::getField($transaction, $lineElement, 'performancecountry'));
                    }

                    if (Util::objectUses(PerformanceDateField::class, $transactionLine)) {
                        $transactionLine->setPerformanceDateFromString(self::getField($transaction, $lineElement, 'performancedate'));
                    }

                    if (Util::objectUses(PerformanceTypeField::class, $transactionLine)) {
                        $transactionLine->setPerformanceTypeFromString(self::getField($transaction, $lineElement, 'performancetype'));
                    }

                    if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine)) {
                        $transactionLine->setPerformanceVatNumber(self::getField($transaction, $lineElement, 'performancevatnumber'));
                    }

                    $transactionLine->setVatCodeFromString(self::getField($transaction, $lineElement, 'vatcode'));
                }

                if ($lineType == LineType::TOTAL()) {
                    if (Util::objectUses(MatchDateField::class, $transactionLine)) {
                        $transactionLine->setMatchDateFromString(self::getField($transaction, $lineElement, 'matchdate'));
                    }

                    if (Util::objectUses(ValueOpenField::class, $transactionLine)) {
                        $valueOpen = self::getField($transaction, $lineElement, 'valueopen') ?: self::getField($transaction, $lineElement, 'openvalue');

                        if ($valueOpen) {
                            $transactionLine->setValueOpenFromFloat($valueOpen);
                        }
                    }

                    if (Util::objectUses(VatBaseTotalField::class, $transactionLine)) {
                        $transactionLine->setVatBaseTotalFromFloat(self::getField($transaction, $lineElement, 'vatbasetotal'));
                    }

                    if (Util::objectUses(VatRepTotalField::class, $transactionLine)) {
                        $transactionLine->setVatRepTotalFromFloat(self::getField($transaction, $lineElement, 'vatreptotal'));
                    }

                    if (Util::objectUses(VatTotalField::class, $transactionLine)) {
                        $transactionLine->setVatTotalFromFloat(self::getField($transaction, $lineElement, 'vattotal'));
                    }
                }

                $transaction->addLine($transactionLine);
            }
        }

        return $transaction;
    }
}