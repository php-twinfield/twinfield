<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Request as Request;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Transaction;

/**
 * A facade to make interaction with the Twinfield service easier when trying to retrieve or set information about
 * Transactions.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific transaction by code, transactionNumber and optionally the office.
     *
     * @param string      $code
     * @param string      $transactionNumber
     * @param string|null $office            Optional. If no office has been passed it will instead take the default
     *                                       office from the passed in Config class.
     * @return Transaction|bool
     */
    public function get($code, $transactionNumber, $office = null)
    {
        if ($this->getLogin()->process()) {
            // Get the secure service
            $service = $this->createService();

            if (!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single transaction
            $request_transaction = new Request\Read\Transaction();
            $request_transaction
                ->setCode($code)
                ->setNumber($transactionNumber)
                ->setOffice($office);

            // Send the Request document and set the response to this instance
            $response = $service->send($request_transaction);
            $this->setResponse($response);

            if ($response->isSuccessful()) {
                return TransactionMapper::map($response);
            }
        }

        return false;
    }

    /**
     * Sends a list of Transaction instances to Twinfield to add or update.
     *
     * If you want to map the response back into an invoice use getResponse()->getResponseDocument()->asXML() into the
     * InvoiceMapper::map() method.
     *
     * @param Transaction[] $transactions
     * @return bool
     */
    public function send(array $transactions): bool
    {
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            $transactionsDocument = new TransactionsDocument();

            foreach ($transactions as $transaction) {
                $transactionsDocument->addTransaction($transaction);
            }

            // Send the DOM document request and set the response
            $response = $service->send($transactionsDocument);
            $this->setResponse($response);

            return $response->isSuccessful();
        }

        return false;
    }
}
