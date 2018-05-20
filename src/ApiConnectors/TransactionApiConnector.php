<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BaseTransaction;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the Twinfield service easier when trying to retrieve or set information about
 * Transactions.
 *
 * @author Dylan Schoenmakers <dylan@opifer.nl>
 */
class TransactionApiConnector extends BaseApiConnector
{
    use BookingReferenceDeletionTrait;

    /**
     * Requests a specific transaction by code, transactionNumber and the office.
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
        $response = $this->sendXmlDocument($request_transaction);

        return TransactionMapper::map($transactionClassName, $response);
    }

    /**
     * @param BaseTransaction $transaction
     * @return BaseTransaction
     * @throws Exception
     */
    public function send(BaseTransaction $transaction): BaseTransaction
    {
        foreach($this->sendAll([$transaction]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * Sends a list of Transaction instances to Twinfield to add or update.
     *
     * @param BaseTransaction[] $transactions
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $transactions): MappedResponseCollection
    {
        Assert::allIsInstanceOf($transactions, BaseTransaction::class);

        $classname = get_class(reset($transactions));

        /*
         * We can have multiple documents sent, so we need to collect all documents.
         */
        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($transactions) as $chunk) {

            $transactionsDocument = new TransactionsDocument();

            foreach ($chunk as $transaction) {
                $transactionsDocument->addTransaction($transaction);
            }

            $responses[] = $this->sendXmlDocument($transactionsDocument);
        }

        return $this->getProcessXmlService()->mapAll(
            $responses,
            "transaction",
            function (Response $subresponse) use ($classname): BaseTransaction {
                return TransactionMapper::map($classname, $subresponse);
            }
        );
    }
}
