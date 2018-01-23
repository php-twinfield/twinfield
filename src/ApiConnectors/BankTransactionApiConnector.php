<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BankTransaction;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Exception;
use Webmozart\Assert\Assert;

class BankTransactionApiConnector extends BaseApiConnector
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

        foreach ($this->getProcessXmlService()->chunk($bankTransactions) as $chunk) {

            $bankTransactionDocument = new BankTransactionDocument();

            foreach ($chunk as $bankTransaction) {
                $bankTransactionDocument->addBankTransaction($bankTransaction);
            }

            $this->getProcessXmlService()->sendDocument($bankTransactionDocument);
        }
    }
}
