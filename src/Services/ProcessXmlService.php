<?php

namespace PhpTwinfield\Services;

use PhpTwinfield\Response\Response;

class ProcessXmlService extends BaseService
{
    protected function WSDL(): string
    {
        return "/webservices/processxml.asmx?wsdl";
    }

    /**
     * @param \DOMDocument $document
     * @return Response The response from the request
     */
    public function sendDOMDocument(\DOMDocument $document): Response
    {
        $result = $this->ProcessXmlString(
            array('xmlRequest' => $document->saveXML())
        );

        // Make a new DOMDocument, and load the response into it
        $responseDocument = new \DOMDocument();
        $responseDocument->loadXML($result->ProcessXmlStringResult);

        return new Response($responseDocument);
    }
}