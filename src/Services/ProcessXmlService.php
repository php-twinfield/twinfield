<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Exception;
use PhpTwinfield\Response\Response;

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
     * @param \DOMDocument $document
     * @return Response The response from the request
     * @throws Exception
     */
    public function sendDocument(\DOMDocument $document): Response
    {
        $result = $this->ProcessXmlString(
            array('xmlRequest' => $document->saveXML())
        );

        // Make a new DOMDocument, and load the response into it
        $responseDocument = new \DOMDocument();
        $responseDocument->loadXML($result->ProcessXmlStringResult);

        $response = new Response($responseDocument);
        $response->assertSuccessful();

        return $response;
    }
}