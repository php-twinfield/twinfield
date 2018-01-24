<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Exception;
use PhpTwinfield\Response\IndividualMappedResponse;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

class ProcessXmlService extends BaseService
{
    /**
     * Advise is to limit the number of children within a parent to 25. So 25 elements within a <general> element, 25
     * elements within e.g. a <transactions> element and so on.
     *
     * @link https://c3.twinfield.com/webservices/documentation/#/GettingStarted/FUP
     */
    private const MAX_CHILDREN = 25;

    protected function WSDL(): string
    {
        return "/webservices/processxml.asmx?wsdl";
    }

    /**
     * @param array $items
     * @return array[]
     */
    public function chunk(array $items): array
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
     * Note that you will probably have to chunk the objects into several documents if you want to send many objects. 
     *
     * @param \DOMDocument $document
     * @return Response The response from the request
     * @throws Exception
     */
    public function sendDocument(\DOMDocument $document): Response
    {
        $result = $this->ProcessXmlString(
            array('xmlRequest' => $document->saveXML())
        );

        $response = Response::fromString($result->ProcessXmlStringResult);
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
    public function mapAll(array $responses, string $individualTag, callable $mapCallback): iterable
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