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
     * Advise is to limit the number of children within a parent to 25. So 25 elements within a <general> element, 25
     * elements within e.g. a <transactions> element and so on.
     *
     * @link https://c3.twinfield.com/webservices/documentation/#/GettingStarted/FUP
     */
    private const MAX_CHILDREN = 25;

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
     * @param array $items
     * @return array[]
     */
    protected function chunk(array $items): array
    {
        return array_chunk($items, self::MAX_CHILDREN);
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
        $response->assertSuccessful();

        return $response;
    }
}
