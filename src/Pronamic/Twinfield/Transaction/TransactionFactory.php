<?php

namespace Pronamic\Twinfield\Transaction;

use \Pronamic\Twinfield\Factory\ParentFactory;


/**
 * TransactionFactory class
 * 
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionFactory extends ParentFactory
{

    /**
     * 
     * @param \Pronamic\Twinfield\Transaction\Transaction $transaction
     * @return boolean
     */
    public function send(Transaction $transaction)
    {
        if ($this->getLogin()->process()) {
            
            // Gets the secure service
            $service = $this->getService();

            $transactionsDocument = new DOM\TransactionsDocument();
            $transactionsDocument->addTransaction($transaction);

            // Send the DOM document request and set the response
            $response = $service->send($transactionsDocument);
            $this->setResponse($response);

            if ($response->isSuccessful()) {
                return true;
            } else {
                // should throw exception?
                return false;
            }
        }
    }

}
