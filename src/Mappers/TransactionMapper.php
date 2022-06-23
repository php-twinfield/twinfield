<?php

namespace PhpTwinfield\Mappers;

use DOMXPath;
use Money\Currency;
use Money\Money;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Exception;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionLineFields\MatchDateField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use PhpTwinfield\Util;

class TransactionMapper
{
    private const DEFAULT_CURRENCY_CODE = 'EUR';

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

        /** @var BaseTransaction $transaction */
        $transaction = new $transactionClassName();
        $transaction->setResult($transactionElement->getAttribute('result'));

        $destiny = $transactionElement->getAttribute('location');
        if (empty($destiny)) {
            /*
             * This field should be sent to Twinfield as 'destiny' attribute and Twinfield should return it as
             * 'location' attribute. But in case of an error elsewhere in this object, Twinfield returns this field as
             * 'destiny' attribute.
             */
            $destiny = $transactionElement->getAttribute('destiny');
        }
        if (!empty($destiny)) {
            $transaction->setDestiny(new Destiny($destiny));
        }

        $autoBalanceVat = $transactionElement->getAttribute('autobalancevat');
        if (!empty($autoBalanceVat)) {
            $transaction->setAutoBalanceVat($autoBalanceVat == 'true');
        }

        $raiseWarning = $transactionElement->getAttribute('raisewarning');
        if (!empty($raiseWarning)) {
            $transaction->setRaiseWarning(Util::parseBoolean($raiseWarning));
        }

        if (!empty($response->getErrorMessages())) {
            \LogService::error("[TransactionMapper map] Got error messages therefore no map could be created. Got error messages: " . print_r($response->getErrorMessages()));
        }

        if (!empty($response->getWarningMessages())) {
            \LogService::warning("[TransactionMapper map] Got warning messages therefore no map could be created. Got warning messages: " . print_r($response->getWarningMessages()));
        }

        $office = new Office();
        $office->setCode(self::getField($transaction, $transactionElement, 'office'));

        $transaction
            ->setOffice($office)
            ->setCode(self::getField($transaction, $transactionElement, 'code'))
            ->setPeriod(self::getField($transaction, $transactionElement, 'period'))
            ->setDateFromString(self::getField($transaction, $transactionElement, 'date'))
            ->setOrigin(self::getField($transaction, $transactionElement, 'origin'))
            ->setFreetext1(self::getField($transaction, $transactionElement, 'freetext1'))
            ->setFreetext2(self::getField($transaction, $transactionElement, 'freetext2'))
            ->setFreetext3(self::getField($transaction, $transactionElement, 'freetext3'));

        $currency = new Currency(self::getField($transaction, $transactionElement, 'currency') ?? self::DEFAULT_CURRENCY_CODE);
        $transaction->setCurrency($currency);

        $number = self::getField($transaction, $transactionElement, 'number');
        if (!empty($number)) {
            $transaction->setNumber($number);
        }

        if (Util::objectUses(DueDateField::class, $transaction)) {
            $value = self::getField($transaction, $transactionElement, 'duedate');

            if ($value !== null) {
                $transaction->setDueDateFromString($value);
            }
        }
        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transaction, $transactionElement, 'invoicenumber'));
        }
        if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
            $transaction
                ->setPaymentReference(self::getField($transaction, $transactionElement, 'paymentreference'));
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
        if ($transaction instanceof CashTransaction) {
            $transaction->setStartvalue(
                Util::parseMoney(
                    self::getField($transaction, $transactionElement, 'startvalue'),
                    $transaction->getCurrency()
                )
            );
        }

        // Parse the transaction lines
        $transactionLineClassName = $transaction->getLineClassName();

        foreach ((new DOMXPath($document))->query('/transaction/lines/line') as $lineElement) {
            self::checkForMessage($transaction, $lineElement);

            /** @var BaseTransactionLine $transactionLine */
            $transactionLine = new $transactionLineClassName();
            $lineType        = $lineElement->getAttribute('type');

            $transactionLine
                ->setLineType(new LineType($lineType))
                ->setId($lineElement->getAttribute('id'))
                ->setDim1(self::getField($transaction, $lineElement, 'dim1'))
                ->setDim2(self::getField($transaction, $lineElement, 'dim2'))
                ->setValue(self::getFieldAsMoney($transaction, $lineElement, 'value', $currency))
                ->setDebitCredit(new DebitCredit(self::getField($transaction, $lineElement, 'debitcredit')))
                ->setBaseValue(self::getFieldAsMoney($transaction, $lineElement, 'basevalue', $currency))
                ->setRate(self::getField($transaction, $lineElement, 'rate'))
                ->setRepValue(self::getFieldAsMoney($transaction, $lineElement, 'repvalue', $currency))
                ->setRepRate(self::getField($transaction, $lineElement, 'reprate'))
                ->setDescription(self::getField($transaction, $lineElement, 'description'))
                ->setMatchStatus(self::getField($transaction, $lineElement, 'matchstatus'))
                ->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'))
                ->setVatCode(self::getField($transaction, $lineElement, 'vatcode'));

            // TODO - according to the docs, the field is called <basevalueopen>, but the examples use <openbasevalue>.
            $baseValueOpen = self::getFieldAsMoney($transaction, $lineElement, 'basevalueopen', $currency) ?: self::getFieldAsMoney($transaction, $lineElement, 'openbasevalue', $currency);
            if ($baseValueOpen) {
                $transactionLine->setBaseValueOpen($baseValueOpen);
            }

            $vatValue = self::getFieldAsMoney($transaction, $lineElement, 'vatvalue', $currency);
            if ($lineType == LineType::DETAIL() && $vatValue) {
                $transactionLine->setVatValue($vatValue);
            }

            $baseline = self::getField($transaction, $lineElement, 'baseline');
            if ($baseline) {
                $transactionLine->setBaseline($baseline);
            }

            if (Util::objectUses(FreeTextFields::class, $transactionLine)) {
                $freetext1 = self::getField($transaction, $lineElement, 'freetext1');
                if ($freetext1) {
                    $transactionLine->setFreetext1($freetext1);
                }

                $freetext2 = self::getField($transaction, $lineElement, 'freetext2');
                if ($freetext2) {
                    $transactionLine->setFreetext2($freetext2);
                }

                $freetext3 = self::getField($transaction, $lineElement, 'freetext3');
                if ($freetext3) {
                    $transactionLine->setFreetext3($freetext3);
                }
            }

            if (Util::objectUses(PerformanceFields::class, $transactionLine)) {
                /** @var BaseTransactionLine|PerformanceFields $transactionLine */
                $performanceType = self::getField($transaction, $lineElement, 'performancetype');
                $transactionLine
                    ->setPerformanceType($performanceType ? new PerformanceType($performanceType) : null)
                    ->setPerformanceCountry(self::getField($transaction, $lineElement, 'performancecountry'))
                    ->setPerformanceVatNumber(self::getField($transaction, $lineElement, 'performancevatnumber'));

                $performanceDate = self::getField($transaction, $lineElement, 'performancedate');

                if ($performanceDate) {
                    $transactionLine->setPerformanceDate(Util::parseDate($performanceDate));
                }
            }
            if (in_array(ValueOpenField::class, class_uses($transactionLine))) {
                // TODO - according to the docs, the field is called <valueopen>, but the examples use <openvalue>.
                $valueOpen = self::getFieldAsMoney($transaction, $lineElement, 'valueopen', $currency) ?: self::getFieldAsMoney($transaction, $lineElement, 'openvalue', $currency);
                if ($valueOpen) {
                    $transactionLine->setValueOpen($valueOpen);
                }
            }
            if (in_array(VatTotalFields::class, class_uses($transactionLine))) {
                $vatTotal = self::getFieldAsMoney($transaction, $lineElement, 'vattotal', $currency);
                if ($vatTotal) {
                    $transactionLine->setVatTotal($vatTotal);
                }

                $vatBaseTotal = self::getFieldAsMoney($transaction, $lineElement, 'vatbasetotal', $currency);
                if ($vatBaseTotal) {
                    $transactionLine->setVatBaseTotal($vatBaseTotal);
                }
            }
            if (Util::objectUses(InvoiceNumberField::class, $transactionLine)) {
                /** @var InvoiceNumberField $transactionLine */
                $invoiceNumber = self::getField($transaction, $lineElement, 'invoicenumber');
                if ($invoiceNumber) {
                    $transactionLine->setInvoiceNumber(self::getField($transaction, $lineElement, 'invoicenumber'));
                }
            }

            if (Util::objectUses(MatchDateField::class, $transactionLine)) {
                /** @var MatchDateField $transactionLine */
                $matchDate = self::getField($transaction, $lineElement, 'matchdate');
                if ($matchDate) {
                    $transactionLine->setMatchDate(Util::parseDate($matchDate));
                }
            }

            $transaction->addLine($transactionLine);
        }

        return $transaction;
    }

    private static function getField(BaseTransaction $transaction, \DOMElement $element, string $fieldTagName): ?string
    {
        $fieldElement = $element->getElementsByTagName($fieldTagName)->item(0);

        if (!isset($fieldElement)) {
            return null;
        }

        self::checkForMessage($transaction, $fieldElement);

        return $fieldElement->textContent;
    }

    private static function getFieldAsMoney(
        BaseTransaction $transaction,
        \DOMElement $element,
        string $fieldTagName,
        Currency $currency
    ): ?Money {
        $fieldValue = self::getField($transaction, $element, $fieldTagName);

        if ($fieldValue === null) {
            return null;
        }

        return new Money((string)(100 * $fieldValue), $currency);
    }

    private static function checkForMessage(BaseTransaction $transaction, \DOMElement $element): void
    {
        if ($element->hasAttribute('msg')) {
            $message = new Message();
            $message->setType($element->getAttribute('msgtype'));
            $message->setMessage($element->getAttribute('msg'));
            $message->setField($element->nodeName);

            $transaction->addMessage($message);
        }
    }
}
