<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\IndividualMappedResponse;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\ProcessXmlService;
use Traversable;
use Webmozart\Assert\Assert;

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

    /**
     * Map an array of Responses to IndividualMappedResponse using a callback.
     *
     * @see IndividualMappedResponse
     * @param Response[] $responses
     * @param string $individualTag Tag that contains each sub response (e.g. "transaction")
     * @param callable $mapCallback The callback should return the mapped object (e.g. a PurchaseTransaction) based on the response.
     * @return IndividualMappedResponse[]|iterable
     */
    protected function mapAll(array $responses, string $individualTag, callable $mapCallback): iterable
    {
        $return = [];

        foreach ($responses as $response) {

            /* $response should already asserted as successful by the called. */
            $document = $response->getResponseDocument();

            /** @var \DOMElement[]|\DOMNodeList $nodeList */
            $nodeList = $document->getElementsByTagName($individualTag);

            Assert::greaterThanEq($nodeList->length, count($responses));

            foreach ($nodeList as $element) {

                $xml = $document->saveXML($element);
                $subResponse = Response::fromString($xml);

                $return[] = new IndividualMappedResponse($subResponse, $mapCallback);
            }
        }

        return $return;
    }
}
