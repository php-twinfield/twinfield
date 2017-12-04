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
     * Sends an ElectronicBankStatement instance to Twinfield to update or add.
     *
     * @throws Exception
     */
    public function send(ElectronicBankStatement $electronicBankStatement): void
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            // Gets a new instance of CustomersDocument and sets the $customer
            $ebsDocument = new ElectronicBankStatementDocument();
            $ebsDocument->addStatement($electronicBankStatement);

            // Send the DOM document request and set the response
            $response = $service->send($ebsDocument);
            $this->setResponse($response);

            if (!$response->isSuccessful()) {
                throw new Exception(implode(", ", $response->getErrorMessages()));
            }
        }
    }
}
