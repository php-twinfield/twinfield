<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use Webmozart\Assert\Assert;

class BankTransactionApiConnector extends ProcessXmlApiConnector
{
    /**
     * Sends a BankTransaction instance to Twinfield to update or add.
     *
     * @param BankTransaction $bankTransaction
     * @throws Exception
     */
    public function send(BankTransaction $bankTransaction): void
    {
        $this->sendAll([$bankTransaction]);
    }

    /**
     * @param BankTransaction[] $bankTransactions
     * @throws Exception
     */
    public function sendAll(array $bankTransactions): void
    {
        Assert::allIsInstanceOf($bankTransactions, BankTransaction::class);
        Assert::notEmpty($bankTransactions);

        // Gets a new instance of ArticlesDocument and sets the $article
        $bankTransactionDocument = new BankTransactionDocument();

        foreach ($bankTransactions as $bankTransaction) {
            $bankTransactionDocument->addBankTransaction($bankTransaction);
        }

        // Send the DOM document request and set the response
        $this->sendDocument($bankTransactionDocument);
    }
}
