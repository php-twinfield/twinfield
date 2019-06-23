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
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

class TransactionMapper extends BaseMapper
{
    /**
     * @param string   $transactionClassName
     * @param Response $response
     * @param AuthenticatedConnection $connection
     *
     * @return iterable|BaseTransaction[]
     * @throws Exception
     */
    public static function mapAll(string $transactionClassName, Response $response, AuthenticatedConnection $connection): iterable
    {
        foreach ($response->getResponseDocument()->getElementsByTagName('transaction') as $transactionElement) {
            yield self::map($transactionClassName, $transactionElement, $connection);
        }
    }

    public static function map(string $transactionClassName, Response $response, AuthenticatedConnection $connection): BaseTransaction
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
            $transaction->setAutoBalanceVat(Util::parseBoolean($autoBalanceVat));
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
            $transaction->setRaiseWarning(Util::parseBoolean($raiseWarning));
        }

        $transaction->setCode(self::getField($transactionElement, 'code', $transaction))
            ->setCurrency(self::parseObjectAttribute(\PhpTwinfield\Currency::class, $transaction, $transactionElement, 'currency'))
            ->setDate(self::parseDateAttribute(self::getField($transactionElement, 'date', $transaction)))
            ->setFreetext1(self::getField($transactionElement, 'freetext1', $transaction))
            ->setFreetext2(self::getField($transactionElement, 'freetext2', $transaction))
            ->setFreetext3(self::getField($transactionElement, 'freetext3', $transaction))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $transaction, $transactionElement, 'office'))
            ->setOrigin(self::getField($transactionElement, 'origin', $transaction))
            ->setNumber(self::getField($transactionElement, 'number', $transaction))
            ->setPeriod(self::getField($transactionElement, 'period', $transaction));
            
        if ($transaction->getOffice() !== null) {
            $currencies = self::getOfficeCurrencies($connection, $transaction->getOffice());
        }

        if (Util::objectUses(CloseAndStartValueFields::class, $transaction)) {
            $transaction->setStartValue(self::parseMoneyAttribute(self::getField($transactionElement, 'startvalue', $transaction), Util::objectToStr($transaction->getCurrency())));
        }

        if (Util::objectUses(DueDateField::class, $transaction)) {
            $transaction->setDueDate(self::parseDateAttribute(self::getField($transactionElement, 'duedate', $transaction)));
        }

        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transactionElement, 'invoicenumber', $transaction));
            $transaction->setInvoiceNumberRaiseWarning(Util::parseBoolean(self::getAttribute($transactionElement, 'invoicenumber', 'raisewarning')));
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
                    ->setBaseValue(self::parseMoneyAttribute(self::getField($lineElement, 'basevalue', $transactionLine), $currencies['base']))
                    ->setComment(self::getField($lineElement, 'comment', $transactionLine))
                    ->setValue(self::parseMoneyAttribute(self::getField($lineElement, 'value', $transactionLine), Util::objectToStr($transaction->getCurrency())))
                    ->setDebitCredit(self::parseEnumAttribute(\PhpTwinfield\Enums\DebitCredit::class, self::getField($lineElement, 'debitcredit', $transactionLine)))
                    ->setDescription(self::getField($lineElement, 'description', $transactionLine))
                    ->setDestOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $transactionLine, $lineElement, 'destoffice'))
                    ->setDim1(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                    ->setId($lineElement->getAttribute('id'))
                    ->setLineType(self::parseEnumAttribute(\PhpTwinfield\Enums\LineType::class, $lineType))
                    ->setMatchStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchStatus::class, self::getField($lineElement, 'matchstatus', $transactionLine)))
                    ->setRate(self::getField($lineElement, 'rate', $transactionLine))
                    ->setRepRate(self::getField($lineElement, 'reprate', $transactionLine))
                    ->setRepValue(self::parseMoneyAttribute(self::getField($lineElement, 'repvalue', $transactionLine), $currencies['reporting']));

                $freeChar = self::getField($lineElement, 'freechar', $transactionLine);

                if ($freeChar !== null && strlen($freeChar) == 1) {
                    $transactionLine->setFreeChar($freeChar);
                }

                if ($transaction instanceof BankTransaction || $transaction instanceof CashTransaction || $transaction instanceof JournalTransaction) {
                    if ($lineType == LineType::DETAIL()) {
                        $baseValueOpen = self::getField($lineElement, 'basevalueopen', $transactionLine) ?: self::getField($lineElement, 'openbasevalue', $transactionLine);

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpen(self::parseMoneyAttribute($baseValueOpen, $currencies['base']));
                        }

                        $transactionLine->setDim2(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));
                        $transactionLine->setDim3(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));
                        $transactionLine->setMatchLevel(self::getField($lineElement, 'matchlevel', $transactionLine));
                        $transactionLine->setRelation(self::getField($lineElement, 'relation', $transactionLine));
                        $transactionLine->setRepValueOpen(self::parseMoneyAttribute(self::getField($lineElement, 'repvalueopen', $transactionLine), $currencies['reporting']));
                    }
                }

                if ($transaction instanceof PurchaseTransaction || $transaction instanceof SalesTransaction) {
                    if ($lineType == LineType::DETAIL()) {
                        $transactionLine->setDim2(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));
                        $transactionLine->setDim3(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));
                    } elseif ($transaction instanceof PurchaseTransaction && $lineType == LineType::VAT()) {
                        $transactionLine->setDim3(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));
                    } elseif ($lineType == LineType::TOTAL()) {
                        $transactionLine->setDim2(self::parseObjectAttribute(null, $transactionLine, $lineElement, 'dim2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));

                        $baseValueOpen = self::getField($lineElement, 'basevalueopen', $transactionLine) ?: self::getField($lineElement, 'openbasevalue', $transactionLine);

                        if ($baseValueOpen) {
                            $transactionLine->setBaseValueOpen(self::parseMoneyAttribute($baseValueOpen, $currencies['base']));
                        }

                        $transactionLine->setMatchLevel(self::getField($lineElement, 'matchlevel', $transactionLine));
                        $transactionLine->setRelation(self::getField($lineElement, 'relation', $transactionLine));
                        $transactionLine->setRepValueOpen(self::parseMoneyAttribute(self::getField($lineElement, 'repvalueopen', $transactionLine), $currencies['reporting']));
                    }
                }

                if ($lineType == LineType::DETAIL()) {
                    if (Util::objectUses(CurrencyDateField::class, $transactionLine)) {
                        $transactionLine->setCurrencyDate(self::parseDateAttribute(self::getField($lineElement, 'currencydate', $transactionLine)));
                    }

                    if (Util::objectUses(InvoiceNumberField::class, $transactionLine)) {
                        $transactionLine->setInvoiceNumber(self::getField($lineElement, 'invoicenumber', $transactionLine));
                    }

                    $transactionLine->setVatBaseValue(self::parseMoneyAttribute(self::getField($lineElement, 'vatbasevalue', $transactionLine), $currencies['base']));
                    $transactionLine->setVatRepValue(self::parseMoneyAttribute(self::getField($lineElement, 'vatrepvalue', $transactionLine), $currencies['reporting']));
                    $transactionLine->setVatValue(self::parseMoneyAttribute(self::getField($lineElement, 'vatvalue', $transactionLine), Util::objectToStr($transaction->getCurrency())));
                } elseif ($lineType == LineType::VAT()) {
                    if (Util::objectUses(BaselineField::class, $transactionLine)) {
                        $transactionLine->setBaseline(self::getField($lineElement, 'baseline', $transactionLine));
                    }

                    $transactionLine->setVatBaseTurnover(self::parseMoneyAttribute(self::getField($lineElement, 'vatbaseturnover', $transactionLine), $currencies['base']));
                    $transactionLine->setVatRepTurnover(self::parseMoneyAttribute(self::getField($lineElement, 'vatrepturnover', $transactionLine), $currencies['reporting']));
                    $transactionLine->setVatTurnover(self::parseMoneyAttribute(self::getField($lineElement, 'vatturnover', $transactionLine), Util::objectToStr($transaction->getCurrency())));
                } elseif ($lineType == LineType::TOTAL()) {
                    if (Util::objectUses(MatchDateField::class, $transactionLine)) {
                        $transactionLine->setMatchDate(self::parseDateAttribute(self::getField($lineElement, 'matchdate', $transactionLine)));
                    }

                    if (Util::objectUses(ValueOpenField::class, $transactionLine)) {
                        $valueOpen = self::getField($lineElement, 'valueopen', $transactionLine) ?: self::getField($lineElement, 'openvalue', $transactionLine);

                        if ($valueOpen) {
                            $transactionLine->setValueOpen(self::parseMoneyAttribute($valueOpen, Util::objectToStr($transaction->getCurrency())));
                        }
                    }

                    if (Util::objectUses(VatBaseTotalField::class, $transactionLine)) {
                        $transactionLine->setVatBaseTotal(self::parseMoneyAttribute(self::getField($lineElement, 'vatbasetotal', $transactionLine), $currencies['base']));
                    }

                    if (Util::objectUses(VatRepTotalField::class, $transactionLine)) {
                        $transactionLine->setVatRepTotal(self::parseMoneyAttribute(self::getField($lineElement, 'vatreptotal', $transactionLine), $currencies['reporting']));
                    }

                    if (Util::objectUses(VatTotalField::class, $transactionLine)) {
                        $transactionLine->setVatTotal(self::parseMoneyAttribute(self::getField($lineElement, 'vattotal', $transactionLine), Util::objectToStr($transaction->getCurrency())));
                    }
                }

                if ($lineType != LineType::TOTAL()) {
                    if (Util::objectUses(PerformanceCountryField::class, $transactionLine)) {
                        $transactionLine->setPerformanceCountry(self::parseObjectAttribute(\PhpTwinfield\Country::class, $transactionLine, $lineElement, 'performancecountry'));
                    }

                    if (Util::objectUses(PerformanceDateField::class, $transactionLine)) {
                        $transactionLine->setPerformanceDate(self::parseDateAttribute(self::getField($lineElement, 'performancedate', $transactionLine)));
                    }

                    if (Util::objectUses(PerformanceTypeField::class, $transactionLine)) {
                        $transactionLine->setPerformanceType(self::parseEnumAttribute(\PhpTwinfield\Enums\PerformanceType::class, self::getField($lineElement, 'performancetype', $transactionLine)));
                    }

                    if (Util::objectUses(PerformanceVatNumberField::class, $transactionLine)) {
                        $transactionLine->setPerformanceVatNumber(self::getField($lineElement, 'performancevatnumber', $transactionLine));
                    }

                    $transactionLine->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $transactionLine, $lineElement, 'vatcode'));
                }

                $transaction->addLine($transactionLine);
            }
        }

        return $transaction;
    }
}