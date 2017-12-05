<?php

namespace PhpTwinfield\Secure;

use PhpTwinfield\Response\Response;

/**
 * Twinfield Soap Client.
 *
 * @author Leon Rowland <leon@rowland.nl>
 */
class SoapClient extends \SoapClient
{
    public function __construct(string $wsdl, array $options = [])
    {
        /*
         * Relies heavily on __getLastResponse() etc.
         */
        $options["trace"]       = true;
        $options["compression"] = SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP;
        $options["cache_wsdl"]  = WSDL_CACHE_BOTH;
        $options["keep_alive"]  = true;

        parent::__construct($wsdl, $options);
    }

    /**
     * Sends a request with the secured client, and loads
     * the result response into Service->response
     *
     * An instance of Twinfield\Response\Response is also returned.
     *
     * @param \DOMDocument $document A class that extended Secure\Document
     * @return Response The response from the request
     */
    public function send(\DOMDocument $document): Response
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
