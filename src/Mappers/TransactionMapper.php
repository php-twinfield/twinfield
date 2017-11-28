<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Exception;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Message\Message;
use PhpTwinfield\Response\Response;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;

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

        foreach ($responseDom->getElementsByTagName('transaction') as $transactionElement) {
            /** @var BaseTransaction $transaction */
            $transaction = new $transactionClassName();

            $transaction
                ->setResult($transactionElement->getAttribute('result'))
                ->setDestiny($transactionElement->getAttribute('location'));

            $autoBalanceVat = $transactionElement->getAttribute('autobalancevat');
            if (!empty($autoBalanceVat)) {
                $transaction->setAutoBalanceVat($autoBalanceVat);
            }

            $raiseWarning = $transactionElement->getAttribute('raisewarning');
            if (!empty($raiseWarning)) {
                $transaction->setRaiseWarning($raiseWarning);
            }

            $transaction
                ->setOffice(self::getField($transaction, $transactionElement, 'office'))
                ->setCode(self::getField($transaction, $transactionElement, 'code'))
                ->setNumber(self::getField($transaction, $transactionElement, 'number'))
                ->setPeriod(self::getField($transaction, $transactionElement, 'period'))
                ->setCurrency(self::getField($transaction, $transactionElement, 'currency'))
                ->setDate(self::getField($transaction, $transactionElement, 'date'))
                ->setOrigin(self::getField($transaction, $transactionElement, 'origin'))
                ->setFreetext1(self::getField($transaction, $transactionElement, 'freetext1'))
                ->setFreetext2(self::getField($transaction, $transactionElement, 'freetext2'))
                ->setFreetext3(self::getField($transaction, $transactionElement, 'freetext3'));

            if (in_array(DueDateField::class, class_uses($transaction))) {
                $transaction->setDueDate(self::getField($transaction, $transactionElement, 'duedate'));
            }
            if (in_array(InvoiceNumberField::class, class_uses($transaction))) {
                $transaction->setInvoiceNumber(self::getField($transaction, $transactionElement, 'invoicenumber'));
            }
            if (in_array(PaymentReferenceField::class, class_uses($transaction))) {
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
                    ->setType($lineElement->getAttribute('type'))
                    ->setId($lineElement->getAttribute('id'))
                    ->setDim1(self::getField($transaction, $lineElement, 'dim1'))
                    ->setDim2(self::getField($transaction, $lineElement, 'dim2'))
                    ->setDebitCredit(self::getField($transaction, $lineElement, 'debitcredit'))
                    ->setValue(self::getField($transaction, $lineElement, 'value'))
                    ->setBaseValue(self::getField($transaction, $lineElement, 'basevalue'))
                    ->setRate(self::getField($transaction, $lineElement, 'rate'))
                    ->setRepValue(self::getField($transaction, $lineElement, 'repvalue'))
                    ->setRepRate(self::getField($transaction, $lineElement, 'reprate'))
                    ->setDescription(self::getField($transaction, $lineElement, 'description'))
                    ->setMatchStatus(self::getField($transaction, $lineElement, 'matchstatus'))
                    ->setMatchLevel(self::getField($transaction, $lineElement, 'matchlevel'))
                    ->setBaseValueOpen(self::getField($transaction, $lineElement, 'basevalueopen'))
                    ->setVatCode(self::getField($transaction, $lineElement, 'vatcode'))
                    ->setVatValue(self::getField($transaction, $lineElement, 'vatvalue'));

                // TODO - according to the docs, the field is called <basevalueopen>, but the examples use <openbasevalue>.
                if (!$transactionLine->getBaseValueOpen()) {
                    $transactionLine->setBaseValueOpen(self::getField($transaction, $lineElement, 'openbasevalue'));
                }

                if (in_array(PerformanceFields::class, class_uses($transactionLine))) {
                    $transactionLine
                        ->setPerformanceType(self::getField($transaction, $lineElement, 'performancetype'))
                        ->setPerformanceCountry(self::getField($transaction, $lineElement, 'performancecountry'))
                        ->setPerformanceVatNumber(self::getField($transaction, $lineElement, 'performancevatnumber'))
                        ->setPerformanceDate(self::getField($transaction, $lineElement, 'performancedate'));
                }
                if (in_array(ValueOpenField::class, class_uses($transactionLine))) {
                    $transactionLine->setValueOpen(self::getField($transaction, $lineElement, 'valueopen'));

                    // TODO - according to the docs, the field is called <valueopen>, but the examples use <openvalue>.
                    if (!$transactionLine->getValueOpen()) {
                        $transactionLine->setValueOpen(self::getField($transaction, $lineElement, 'openvalue'));
                    }
                }
                if (in_array(VatTotalFields::class, class_uses($transactionLine))) {
                    $transactionLine
                        ->setVatTotal(self::getField($transaction, $lineElement, 'vattotal'))
                        ->setVatBaseTotal(self::getField($transaction, $lineElement, 'vatbasetotal'));
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
