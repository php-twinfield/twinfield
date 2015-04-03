<?php
namespace Pronamic\Twinfield\Transaction\Mapper;

use \Pronamic\Twinfield\Transaction\Transaction;
use \Pronamic\Twinfield\Transaction\TransactionLine;
use \Pronamic\Twinfield\Response\Response;

class TransactionMapper
{
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
            'number'        => 'setNumber'
        );
        
        // Make new Transaction Object
        $transaction = new Transaction();
        
        // Get the top level transaction element
        $transactionElement = $responseDOM->getElementsByTagName('transaction')->item(0);
        
        // Set the destiny/location
        $location = $transactionElement->getAttribute('location');
        $transaction->setDestiny($location);
        
        // Set the raise warning
        $raiseWarning = $transactionElement->getAttribute('raisewarning');
        $transaction->setRaiseWarning($raiseWarning);
        
        // Go through each tag and call the method if a value is set
        foreach ($transactionTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);
            
            if (isset($_tag) && isset($_tag->textContent)) {
                $transaction->$method($_tag->textContent);
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
            'vatbasetotal'     => 'setVatBaseTotal',
            'customersupplier' => 'setCustomerSupplier',
            'openvalue'        => 'setOpenValue',
            'openbasevalue'    => 'setOpenBaseValue',
            'repvalue'         => 'setRepValue',
            'matchlevel'       => 'setMatchLevel',
            'matchstatus'      => 'setMatchStatus'
        );
        
        foreach ($responseDOM->getElementsByTagName('line') as $lineDOM) {
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
        
        return $transaction;
    }
}
