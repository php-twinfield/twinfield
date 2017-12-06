<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\DomDocuments\ElectronicBankStatementDocument;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Request as Request;
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
     * @param array $statements
     * @throws Exception
     */
    public function sendAll(array $statements)
    {
        Assert::allIsInstanceOf($statements, ElectronicBankStatement::class);
        Assert::notEmpty($statements);

        $document = new ElectronicBankStatementDocument();

        foreach ($statements as $statement) {
            $document->addStatement($statement);
        }

        $this->sendDocument($document);
    }
}
