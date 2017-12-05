<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\BaseTransaction;
use Webmozart\Assert\Assert;

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
     * @param string $transactionClassName
     * @param string $code
     * @param string $transactionNumber
     * @param Office $office
     * @throws Exception
     * @return BaseTransaction
     */
    public function get(
        string $transactionClassName,
        string $code,
        string $transactionNumber,
        Office $office
    ): BaseTransaction {
        // Make a request to read a single transaction
        $request_transaction = new Request\Read\Transaction();
        $request_transaction
            ->setCode($code)
            ->setNumber($transactionNumber)
            ->setOffice($office);

        // Send the Request document and set the response to this instance
        $response = $this->sendDocument($request_transaction);

        return TransactionMapper::map($transactionClassName, $response);
    }

    /**
     * Sends a list of Transaction instances to Twinfield to add or update.
     *
     * If you want to map the response back into an invoice use getResponse()->getResponseDocument()->asXML() into the
     * InvoiceMapper::map() method.
     *
     * @param BaseTransaction[] $transactions
     * @throws Exception
     */
    public function send(array $transactions): void
    {
        Assert::allIsInstanceOf($transactions, BaseTransaction::class);

        $transactionsDocument = new TransactionsDocument();

        foreach ($transactions as $transaction) {
            $transactionsDocument->addTransaction($transaction);
        }

        // Send the DOM document request and set the response
        $this->sendDocument($transactionsDocument);
    }
}
