<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\ProcessXmlService;

/**
 * All Factories used by all components extend this factory for common shared methods that help normalize the usage
 * between different components.
 *
 * @author Leon Rowland <leon@rowland.nl>
 */
abstract class ProcessXmlApiConnector extends BaseApiConnector
{
    /**
     * @var ProcessXmlService
     */
    protected $service;

    /**
     * The service that is needed by this connector.
     *
     * Defaults to the ProcessXml Service.
     *
     * @return Services
     */
    final protected function getRequiredWebservice(): Services
    {
        return Services::PROCESSXML();
    }

    /**
     * Send a DOMDocument to the Twinfield service.
     *
     * A document can be both an object for storage or a search / retrieval request.
     *
     * If there is an error, an exception is thrown.
     *
     * @param \DOMDocument $DOMDocument
     * @return Response
     * @throws Exception
     */
    protected function sendDocument(\DOMDocument $DOMDocument): Response
    {
        // Send the DOM document request and set the response
        $response = $this->service->sendDOMDocument($DOMDocument);

        if (!$response->isSuccessful()) {
            throw new Exception(implode(", ", $response->getErrorMessages()));
        }

        return $response;
    }
}
