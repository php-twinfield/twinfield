<?php

namespace PhpTwinfield\Mappers;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionLine;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use PhpTwinfield\Util;

class TransactionMapper
{
    /**
     * @param string   $transactionClassName
     * @param Response $response
     *
     * @return BaseTransaction[]
     * @throws Exception
     */
    public static function map(string $transactionClassName, Response $response): array
    {
        if (!is_a($transactionClassName, BaseTransaction::class, true)) {
            throw Exception::invalidTransactionClassName($transactionClassName);
        }

        $transactions = [];
        $responseDom  = $response->getResponseDocument();

        /** @var \DOMElement $transactionElement */
        foreach ($responseDom->getElementsByTagName('transaction') as $transactionElement) {
            /** @var BaseTransaction $transaction */
            $transaction = new $transactionClassName();

            $transaction
                ->setResult($transactionElement->getAttribute('result'))
                ->setDestiny(new Destiny($transactionElement->getAttribute('location')));

            $autoBalanceVat = $transactionElement->getAttribute('autobalancevat');
            if (!empty($autoBalanceVat)) {
                $transaction->setAutoBalanceVat($autoBalanceVat == 'true');
            }

            $raiseWarning = $transactionElement->getAttribute('raisewarning');
            if (!empty($raiseWarning)) {
                $transaction->setRaiseWarning(Util::parseBoolean($raiseWarning));
            }

            $office = new Office();
            $office->setCode(self::getField($transaction, $transactionElement, 'office'));

            $transaction
                ->setOffice($office)
                ->setCode(self::getField($transaction, $transactionElement, 'code'))
                ->setNumber(self::getField($transaction, $transactionElement, 'number'))
                ->setPeriod(self::getField($transaction, $transactionElement, 'period'))
                ->setCurrency(self::getField($transaction, $transactionElement, 'currency'))
                ->setDateFromString(self::getField($transaction, $transactionElement, 'date'))
                ->setOrigin(self::getField($transaction, $transactionElement, 'origin'))
                ->setFreetext1(self::getField($transaction, $transactionElement, 'freetext1'))
                ->setFreetext2(self::getField($transaction, $transactionElement, 'freetext2'))
                ->setFreetext3(self::getField($transaction, $transactionElement, 'freetext3'));

            if (Util::objectUses(DueDateField::class, $transaction)) {
                $transaction->setDueDateFromString(self::getField($transaction, $transactionElement, 'duedate'));
            }
            if (Util::objectUses(InvoiceNumberField::class, $transaction)) {
                $transaction->setInvoiceNumber(self::getField($transaction, $transactionElement, 'invoicenumber'));
            }
            if (Util::objectUses(PaymentReferenceField::class, $transaction)) {
                $transaction
                    ->setPaymentReference(self::getField($transaction, $transactionElement, 'paymentreference'));
            }

            if ($transaction instanceof SalesTransaction) {
                $transaction->setOriginReference(self::getField($transaction, $transactionElement, 'originreference'));
            }
            if ($transaction instanceof JournalTransaction) {
                $transaction->setRegime(self::getField($transaction, $transactionElement, 'regime'));
            }

            // Parse the transaction lines
            $transactionLineClassName = $transaction->getLineClassName();

            foreach ($transactionElement->getElementsByTagName('line') as $lineElement) {
                self::checkForMessage($transaction, $lineElement);

                /** @var BaseTransactionLine $transactionLine */
                $transactionLine = new $transactionLineClassName();

                $transactionLine
                    ->setLineType(new LineType($lineElement->getAttribute('type')))
                    ->setId($lineElement->getAttribute('id'))
                    ->setDim1(self::getField($transaction, $lineElement, 'dim1'))
                    ->setDim2(self::getField($transaction, $lineElement, 'dim2'))
                    ->setValue(Money::EUR(100 * self::getField($transaction, $lineElement, 'value')))
                    ->setDebitCredit(new DebitCredit(self::getField($transaction, $lineElement, 'debitcredit')))
                    ->setBaseValue(Money::EUR(100 * self::getField($transaction, $lineElement, 'basevalue')))
                    ->setRate(self::getField($transaction, $lineElement, 'rate'))
                    ->setRepValue(Money::EUR(100 * self::getField($transaction, $lineElement, 'repvalue')))
                    ->setRepRate(self::getField($transaction, $lineElement, 'reprate'))
                    ->setDescription(self::getField($transaction, $lineElement, 'description'))
                    ->setMatchStatus(self::getField($transaction, $lineElement, 'matchstatus'))
                    ->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'))
                    ->setVatCode(self::getField($transaction, $lineElement, 'vatcode'));

                // TODO - according to the docs, the field is called <basevalueopen>, but the examples use <openbasevalue>.
                $baseValueOpen = self::getField($transaction, $lineElement, 'basevalueopen') ?: self::getField($transaction, $lineElement, 'openbasevalue');
                if ($baseValueOpen) {
                    $transactionLine->setBaseValueOpen(Money::EUR(100 * $baseValueOpen));
                }

                $vatValue = self::getField($transaction, $lineElement, 'vatvalue');
                if ($vatValue) {
                    $transactionLine->setVatValue(Money::EUR(100 * $vatValue));
                }

                if (Util::objectUses(PerformanceFields::class, $transactionLine)) {
                    $transactionLine
                        ->setPerformanceType(self::getField($transaction, $lineElement, 'performancetype'))
                        ->setPerformanceCountry(self::getField($transaction, $lineElement, 'performancecountry'))
                        ->setPerformanceVatNumber(self::getField($transaction, $lineElement, 'performancevatnumber'));

                    $performanceDate = self::getField($transaction, $lineElement, 'performancedate');

                    if ($performanceDate) {
                        $transactionLine->setPerformanceDate(Util::parseDate($performanceDate));
                    }
                }
                if (in_array(ValueOpenField::class, class_uses($transactionLine))) {
                    // TODO - according to the docs, the field is called <valueopen>, but the examples use <openvalue>.
                    $valueOpen = self::getField($transaction, $lineElement, 'valueopen') ?: self::getField($transaction, $lineElement, 'openvalue');
                    if ($valueOpen) {
                        $transactionLine->setValueOpen(Money::EUR(100 * $valueOpen));
                    }
                }
                if (in_array(VatTotalFields::class, class_uses($transactionLine))) {
                    $vatTotal = self::getField($transaction, $lineElement, 'vattotal');
                    if ($vatTotal) {
                        $transactionLine->setVatTotal(Money::EUR(100 * $vatTotal));
                    }

                    $vatBaseTotal = self::getField($transaction, $lineElement, 'vatbasetotal');
                    if ($vatBaseTotal) {
                        $transactionLine->setVatBaseTotal(Money::EUR(100 * $vatBaseTotal));
                    }
                }

                if ($transactionLine instanceof JournalTransactionLine) {
                    $transactionLine->setInvoiceNumber(self::getField($transaction, $lineElement, 'invoicenumber'));
                }

                $transaction->addLine($transactionLine);
            }

            $transactions[] = $transaction;
        }

        return $transactions;
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
