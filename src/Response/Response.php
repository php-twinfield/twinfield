<?php

namespace PhpTwinfield\Response;

use PhpTwinfield\Exception;

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
     * \PhpTwinfield\Secure\Service class.
     *
     * @var \DOMNode
     */
    private $responseDocument;

    /**
     * Utility function to quickly create new Response objects.
     *
     * @param string $xml
     * @return Response
     */
    public static function fromString(string $xml)
    {
        $document = new \DOMDocument();
        $document->loadXML($xml);

        return new self($document);
    }
    
    public function __construct(\DOMDocument $responseDocument)
    {
        $this->responseDocument = $responseDocument;
    }

    /**
     * Returns the raw DOMDocument response.
     */
    public function getResponseDocument(): \DOMDocument
    {
        return $this->responseDocument;
    }

    /**
     * Will determine if the response was a success or not.  It does
     * this by getting the root element, and looking for the
     * attribute result.  If it doesn't equal 1, then it failed.
     *
     * Note that is is also possible that the result property is individual records.
     *
     * @throws Exception
     */
    public function assertSuccessful(): void
    {
        $responseValue = $this->responseDocument->documentElement->getAttribute('result');

        if ("" === $responseValue) {

            $successful = $failed = 0;

            $xpath = new \DOMXPath($this->responseDocument);

            /** @var \DOMElement $resultAttribute */
            foreach ($xpath->query("*[@result]") as $resultAttribute) {

                if ("1" === $resultAttribute->getAttribute("result")) {
                    $successful++;
                } else {
                    $failed++;
                }
            }

            if ($failed === 0) {
                return;
            }

            throw new Exception("Not all items were processed successfully by Twinfield: {$successful} success / {$failed} failed.");
        }

        if ("1" !== $responseValue) {
            throw new Exception(implode(", ", $this->getErrorMessages()));
        }
    }

    /**
     * Will return an array of all messages found by type
     * in the response document.
     *
     * @return string[]
     */
    private function getMessages(string $type): array
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
     * @since 0.0.1
     *
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return $this->getMessages('error');
    }

    /**
     * Will return an array of all warning messages found
     * in the response document.
     *
     * @since 0.0.1
     *
     * @return string[]
     */
    public function getWarningMessages(): array
    {
        return $this->getMessages('warning');
    }
}
