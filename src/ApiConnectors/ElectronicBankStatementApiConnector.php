<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\DomDocuments\ElectronicBankStatementDocument;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Request as Request;

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
        $document = new ElectronicBankStatementDocument();
        $document->addStatement($statement);

        $this->sendDocument($document);
    }
}
