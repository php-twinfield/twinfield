<?php

namespace Pronamic\Twinfield\Transaction\Mapper;

use Pronamic\Twinfield\Message\Message;
use Pronamic\Twinfield\Response\Response;
use Pronamic\Twinfield\Transaction\Transaction;
use Pronamic\Twinfield\Transaction\TransactionLine;

class TransactionMapper
{
    /**
     * @param Response $response
     *
     * @return Transaction{}
     */
    public static function map(Response $response)
    {
        // Get the raw DOMDocument response
        $responseDOM = $response->getResponseDocument();

        // All tags for the transaction
        $transactionTags = array(
            'code'          => 'setCode',
            'currency'      => 'setCurrency',
            'date'          => 'setDate',
            'period'        => 'setPeriod',
            'invoicenumber' => 'setInvoiceNumber',
            'office'        => 'setOffice',
            'duedate'       => 'setDueDate',
            'origin'        => 'setOrigin',
            'number'        => 'setNumber',
            'freetext1'     => 'setFreetext1',
            'freetext2'     => 'setFreetext2',
            'freetext3'     => 'setFreetext3',
        );


        // Make new Transaction Object
        $transactions = array();

        // Get the top level transaction element
        $transactionElements
            = $responseDOM->getElementsByTagName('transaction');

        foreach ($transactionElements as $transactionElement) {

            // create new transaction
            $transaction = new Transaction();

            // set the result
            $result = $transactionElement->getAttribute('result');
            $transaction->setResult($result);

            // Set the destiny/location
            $location = $transactionElement->getAttribute('location');
            $transaction->setDestiny($location);

            // Set the raise warning
            $raiseWarning = $transactionElement->getAttribute('raisewarning');
            $transaction->setRaiseWarning($raiseWarning);

            // Go through each tag and call the method if a value is set
            foreach ($transactionTags as $tag => $method) {
                $_tag = $transactionElement->getElementsByTagName($tag)
                    ->item(0);

                if (isset($_tag) && isset($_tag->textContent)) {
                    $transaction->$method($_tag->textContent);
                }

                // msg
                if (isset($_tag) && $_tag->hasAttribute('msg')) {
                    $message = new Message();
                    $message->setType($_tag->getAttribute('msgtype'));
                    $message->setMessage($_tag->getAttribute('msg'));
                    $message->setField($tag);
                    $transaction->addMessage($message);
                }
            }

            $lineTags = array(
                'dim1'             => 'setDim1',
                'dim2'             => 'setDim2',
                'value'            => 'setValue',
                'debitcredit'      => 'setDebitCredit',
                'description'      => 'setDescription',
                'rate'             => 'setRate',
                'basevalue'        => 'setBaseValue',
                'reprate'          => 'setRepRate',
                'vatcode'          => 'setVatCode',
                'vattotal'         => 'setVatTotal',
                'vatvalue'         => 'setVatValue',
                'vatbasetotal'     => 'setVatBaseTotal',
                'customersupplier' => 'setCustomerSupplier',
                'basevalueopen'    => 'setBaseValueOpen',
                'valueopen'        => 'setValueOpen',
                'repvalue'         => 'setRepValue',
                'matchlevel'       => 'setMatchLevel',
                'matchstatus'      => 'setMatchStatus',
            );

            foreach (
                $transactionElement->getElementsByTagName('line') as $lineDOM
            ) {
                $temp_line = new TransactionLine();

                $lineType = $lineDOM->getAttribute('type');
                $temp_line->setType($lineType);

                $lineID = $lineDOM->getAttribute('id');
                $temp_line->setID($lineID);

                foreach ($lineTags as $tag => $method) {
                    $_tag = $lineDOM->getElementsByTagName($tag)->item(0);

                    if (isset($_tag) && isset($_tag->textContent)) {
                        $temp_line->$method($_tag->textContent);
                    }
                }

                $transaction->addLine($temp_line);
                unset($lineType);
                unset($temp_line);
            }

            // add
            $transactions[] = $transaction;
        }

        // for backwards compatibility, return only the first instance if there is only one
        return count($transactions) == 1 ? $transactions[0] : $transactions;
    }
}
