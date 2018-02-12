<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\ElectronicBankStatementDocument;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Electronic Bank Statements.
 */
class ElectronicBankStatementApiConnector extends BaseApiConnector
{
    /**
     * @param ElectronicBankStatement $statement
     * @throws Exception
     */
    public function send(ElectronicBankStatement $statement): void
    {
        $this->sendAll([$statement]);
    }

    /**
     * @param ElectronicBankStatement[] $statements
     * @throws Exception
     */
    public function sendAll(array $statements): void
    {
        Assert::allIsInstanceOf($statements, ElectronicBankStatement::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($statements) as $chunk) {

            $document = new ElectronicBankStatementDocument();

            foreach ($chunk as $statement) {
                $document->addStatement($statement);
            }

            $responses[] = $this->sendXmlDocument($document);
        }

        foreach ($responses as $response) {
            $response->assertSuccessful();
        }
    }
}
