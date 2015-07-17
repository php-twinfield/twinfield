<?php

namespace Pronamic\Twinfield\Response;

/**
 * Response class.
 *
 * Handles the response from a request.  Has the option
 * to determine if the response was a success or not.
 *
 * Can return an array of error messages retrieved from the response
 *
 * @since 0.0.1
 *
 * @uses \DOMDocument
 * @uses \DOMXPath
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 *
 * @version 0.0.1
 */
class Response
{
    /**
     * Holds the response, loaded in from the
     * \Pronamic\Twinfield\Secure\Service class.
     *
     * @var \DOMDocument
     */
    private $responseDocument;

    public function __construct(\DOMDocument $responseDocument)
    {
        $this->responseDocument = $responseDocument;
    }

    /**
     * Returns the raw DOMDocument response.
     *
     * @since 0.0.1
     *
     * @return \DOMDocument
     */
    public function getResponseDocument()
    {
        return $this->responseDocument;
    }

    /**
     * Will determine if the response was a success or not.  It does
     * this by getting the root element, and looking for the
     * attribute result.  If it doesn't equal 1, then it failed.
     *
     * @since 0.0.1
     * @see http://remcotolsma.nl/wp-content/uploads/Twinfield-Webservices-Manual.pdf
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $responseValue = $this->responseDocument->documentElement->getAttribute('result');

        return (bool) $responseValue;
    }

    /**
     * Will return an array of all messages found by type
     * in the response document.
     *
     * @return array
     */
    private function getMessages($type)
    {
        $xpath = new \DOMXPath($this->responseDocument);

        $errors = array();

        $rowNodes = $xpath->query('//*[@msgtype="'.$type.'"]');
        foreach ($rowNodes as $rowNode) {
            $errors[] = $rowNode->getAttribute('msg');
        }

        return $errors;
    }

    /**
     * Will return an array of all error messages found
     * in the response document.
     *
     * It is recommended to run this function after a
     * isSuccessful check.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->getMessages('error');
    }

    /**
     * Will return an array of all warning messages found
     * in the response document.
     *
     * It is recommended to run this function after a
     * isSuccessful check.
     *
     * @since 0.0.1
     *
     * @return array
     */
    public function getWarningMessages()
    {
        return $this->getMessages('warning');
    }
}
