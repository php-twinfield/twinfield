<?php

namespace PhpTwinfield\Mappers;

use DOMElement;
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
use PhpTwinfield\Transactions\MatchLine;
use PhpTwinfield\Transactions\MatchSet;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionLine;
use PhpTwinfield\Transactions\TransactionLineFields\MatchDateField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use PhpTwinfield\Util;
use UnexpectedValueException;

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

        $header = self::getFirstChildElementByName($transactionElement, 'header');
        if ($header === null) {
            throw new UnexpectedValueException('Transaction section is missing a header section');
        }

        $office = new Office();
        $office->setCode(self::getField($transaction, $header, 'office'));

        $transaction
            ->setOffice($office)
            ->setCode(self::getField($transaction, $header, 'code'))
            ->setPeriod(self::getField($transaction, $header, 'period'))
            ->setDateFromString(self::getField($transaction, $header, 'date'))
            ->setOrigin(self::getField($transaction, $header, 'origin'))
            ->setFreetext1(self::getField($transaction, $header, 'freetext1'))
            ->setFreetext2(self::getField($transaction, $header, 'freetext2'))
            ->setFreetext3(self::getField($transaction, $header, 'freetext3'));

        $currency = new Currency(self::getField($transaction, $header, 'currency') ?? self::DEFAULT_CURRENCY_CODE);
        $transaction->setCurrency($currency);

        $number = self::getField($transaction, $header, 'number');
        if (!empty($number)) {
            $transaction->setNumber($number);
        }

        if (Util::objectUses(DueDateField::class, $transaction)) {
            $value = self::getField($transaction, $header, 'duedate');

            if ($value !== null) {
                $transaction->setDueDateFromString($value);
            }
        }
        if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
            $transaction->setInvoiceNumber(self::getField($transaction, $header, 'invoicenumber'));
        }
        if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
            $transaction
                ->setPaymentReference(self::getField($transaction, $header, 'paymentreference'));
        }
        if (Util::objectUses(StatementNumberField::class, $transaction)) {
            $transaction->setStatementnumber(self::getField($transaction, $header, 'statementnumber'));
        }

        if ($transaction instanceof SalesTransaction) {
            $transaction->setOriginReference(self::getField($transaction, $header, 'originreference'));
        }
        if ($transaction instanceof JournalTransaction) {
            $transaction->setRegime(self::getField($transaction, $header, 'regime'));
        }
        if ($transaction instanceof CashTransaction) {
            $transaction->setStartvalue(
                Util::parseMoney(
                    self::getField($transaction, $header, 'startvalue'),
                    $transaction->getCurrency()
                )
            );
        }

        // Parse the transaction lines
        $transactionLineClassName = $transaction->getLineClassName();

        /** @var DOMElement $lineElement */
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

            $matches = $lineElement->getElementsByTagName('matches')->item(0);
            if ($matches !== null) {
                self::parseMatches($transaction, $transactionLine, $matches);
            }

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

            $currencyDate = self::getField($transaction, $lineElement, 'currencydate');
            if (!empty($currencyDate)) {
                $transactionLine->setCurrencyDate(Util::parseDate($currencyDate));
            }

            $transaction->addLine($transactionLine);
        }

        return $transaction;
    }

    private static function getFirstChildElementByName(DOMElement $element, string $fieldTagName): ?DOMElement
    {
        $fieldElement = null;
        foreach ($element->childNodes as $node) {
            if ($node->nodeName === $fieldTagName) {
                $fieldElement = $node;
                break;
            }
        }

        return $fieldElement;
    }

    private static function getField(BaseTransaction $transaction, DOMElement $element, string $fieldTagName): ?string
    {
        $fieldElement = self::getFirstChildElementByName($element, $fieldTagName);

        if (!isset($fieldElement)) {
            return null;
        }

        self::checkForMessage($transaction, $fieldElement);

        return $fieldElement->textContent;
    }

    private static function getFieldAsMoney(
        BaseTransaction $transaction,
        DOMElement $element,
        string $fieldTagName,
        Currency $currency
    ): ?Money {
        $fieldValue = self::getField($transaction, $element, $fieldTagName);

        if ($fieldValue === null) {
            return null;
        }

        return new Money((string)(100 * $fieldValue), $currency);
    }

    private static function checkForMessage(BaseTransaction $transaction, DOMElement $element): void
    {
        if ($element->hasAttribute('msg')) {
            $message = new Message();
            $message->setType($element->getAttribute('msgtype'));
            $message->setMessage($element->getAttribute('msg'));
            $message->setField($element->nodeName);

            $transaction->addMessage($message);
        }
    }

    private static function parseMatches(BaseTransaction $baseTransaction, BaseTransactionLine $transactionLine, DOMElement $element): void
    {
        /** @var DOMElement $set */
        foreach ($element->getElementsByTagName('set') as $set) {
            $status = Destiny::from($set->getAttribute('status'));
            $matchDate = Util::parseDate(self::getField($baseTransaction, $set, 'matchdate'));
            $matchValue = self::getFieldAsMoney($baseTransaction, $set, 'matchvalue', $baseTransaction->getCurrency());

            $matchLines = [];
            /** @var DOMElement $lines */
            $lines = $set->getElementsByTagName('lines')->item(0);
            /** @var DOMElement $line */
            foreach ($lines->childNodes as $line) {
                if ($line->nodeName !== 'line') {
                    continue;
                }
                $code = (string)self::getField($baseTransaction, $line, 'code');
                $number = (int)self::getField($baseTransaction, $line, 'number');
                $lineNum = (int)self::getField($baseTransaction, $line, 'line');
                $lineMatchValue = self::getFieldAsMoney($baseTransaction, $line, 'matchvalue', $baseTransaction->getCurrency());

                $matchLines[] = new MatchLine($code, $number, $lineNum, $lineMatchValue);
            }
            $transactionLine->addMatch(new MatchSet($status, $matchDate, $matchValue, $matchLines));
        }
    }
}
