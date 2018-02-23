<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\BankTransactionMapper;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

class BankTransactionApiConnector extends BaseApiConnector
{
    use BookingReferenceDeletionTrait;

    /**
     * Sends a BankTransaction instance to Twinfield to update or add.
     *
     * @param BankTransaction $bankTransaction
     * @return BankTransaction
     * @throws Exception
     */
    public function send(BankTransaction $bankTransaction): BankTransaction
    {
        $bankTransactionResponses = $this->sendAll([$bankTransaction]);

        Assert::count($bankTransactionResponses, 1);

        foreach ($bankTransactionResponses as $bankTransactionResponse) {
            return $bankTransactionResponse->unwrap();
        }
    }

    /**
     * @param BankTransaction[] $bankTransactions
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $bankTransactions): MappedResponseCollection
    {
        Assert::allIsInstanceOf($bankTransactions, BankTransaction::class);

        /*
         * We can have multiple documents sent, so we need to collect all documents.
         */
        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($bankTransactions) as $chunk) {

            $bankTransactionDocument = new BankTransactionDocument();

            foreach ($chunk as $bankTransaction) {
                $bankTransactionDocument->addBankTransaction($bankTransaction);
            }

            $responses[] = $this->sendXmlDocument($bankTransactionDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "transaction", function(Response $subresponse): BankTransaction {
            return BankTransactionMapper::map($subresponse->getResponseDocument());
        });
    }
}
