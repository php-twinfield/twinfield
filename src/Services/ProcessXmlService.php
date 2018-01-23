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

        return Response::fromString($result->ProcessXmlStringResult);
    }
}