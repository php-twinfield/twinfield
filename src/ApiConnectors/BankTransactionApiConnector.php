<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\BookingReference;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\DomDocuments\BookingReferenceDeletionDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\BankTransactionMapper;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

class BankTransactionApiConnector extends BaseApiConnector
{
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
     * Delete a transaction by its booking reference.
     *
     * @param BookingReference $bookingReference
     * @param string $reason A textual reason that can be shown to humans.
     * @throws Exception
     */
    public function delete(BookingReference $bookingReference, string $reason): void
    {
        $document = new BookingReferenceDeletionDocument($bookingReference, $reason);
        
        $response = $this->getProcessXmlService()->sendDocument($document);
        $response->assertSuccessful();
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
