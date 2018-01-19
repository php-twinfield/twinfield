<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\BankTransactionMapper;
use PhpTwinfield\Mappers\TransactionMapper;
use PhpTwinfield\Response\IndividualMappedResponse;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

class BankTransactionApiConnector extends ProcessXmlApiConnector
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
        $responses = $this->sendAll([$bankTransaction]);

        Assert::count($responses, 1);

        foreach ($responses as $response) {
            return $response->get();
        }
    }

    /**
     * @param BankTransaction[] $bankTransactions
     * @return IndividualMappedResponse[]|array
     * @throws Exception
     */
    public function sendAll(array $bankTransactions): iterable
    {
        Assert::allIsInstanceOf($bankTransactions, BankTransaction::class);

        /*
         * We can have multiple documents sent, so we need to collect all documents.
         */
        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->chunk($bankTransactions) as $chunk) {

            $bankTransactionDocument = new BankTransactionDocument();

            foreach ($chunk as $bankTransaction) {
                $bankTransactionDocument->addBankTransaction($bankTransaction);
            }

            $responses[] = $this->sendDocument($bankTransactionDocument);
        }

        $return = [];

        foreach ($responses as $response) {

            /* $response was already asserted as successful. */

            $document = $response->getResponseDocument();

            /** @var \DOMElement $element */
            foreach ($document->getElementsByTagName("transaction") as $element) {

                $xml = $document->saveXML($element);
                $subresponse = Response::fromString($xml);

                $return[] = new IndividualMappedResponse($subresponse, function(Response $subresponse): BankTransaction {
                    return BankTransactionMapper::map($subresponse->getResponseDocument());
                });
            }
        }

        return $return;
    }
}
