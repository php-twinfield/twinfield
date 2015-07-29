<?php

namespace Pronamic\Twinfield\Transaction;

use Pronamic\Twinfield\Factory\ParentFactory;
use Pronamic\Twinfield\Request as Request;

/**
 * TransactionFactory class.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionFactory extends ParentFactory
{
    public function get($code, $number, $office = null)
    {
        if ($this->getLogin()->process()) {

            // Get the secure service
            $service = $this->getService();

            if (!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single transaction
            $request_transaction = new Request\Read\Transaction();
            $request_transaction
                ->setCode($code)
                ->setNumber($number)
                ->setOffice($office);

            // Send the Request document and set the response to this instance
            $response = $service->send($request_transaction);
            $this->setResponse($response);

            if ($response->isSuccessful()) {
                return Mapper\TransactionMapper::map($response);
            } else {
                return false;
            }
        }
    }

    /**
     * @param \Pronamic\Twinfield\Transaction\Transaction $transaction|Array Either one instance or an array of transaction instances
     *
     * @return bool
     */
    public function send($transaction)
    {
        if ($this->getLogin()->process()) {

            // Gets the secure service
            $service = $this->getService();

            $transactionsDocument = new DOM\TransactionsDocument();

            if (is_array($transaction)) {
                foreach ($transaction as $t) {
                    $transactionsDocument->addTransaction($t);
                }
            } else {
                $transactionsDocument->addTransaction($transaction);
            }

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
