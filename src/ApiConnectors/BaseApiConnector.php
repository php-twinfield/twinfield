<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Secure\Connection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;

abstract class BaseApiConnector
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string[]
     */
    private const RETRY_REQUEST_EXCEPTION_MESSAGES = [
        "Error Fetching http headers",
        "SSL: Connection reset by peer",
        "Your logon credentials are not valid anymore. Try to log on again."
    ];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @see sendXmlDocument()
     * @throws Exception
     */
    protected function getProcessXmlService(): ProcessXmlService
    {
        return $this->connection->getAuthenticatedClient(Services::PROCESSXML());
    }

    /**
     * Send the Document using the Twinfield XML service.
     *
     * Will automatically reconnect and recover from login / connection errors.
     *
     * @param \DOMDocument $document
     * @return \PhpTwinfield\Response\Response
     * @throws Exception
     */
    public function sendXmlDocument(\DOMDocument $document) {
        try {
            return $this->getProcessXmlService()->sendDocument($document);
        } catch (\SoapFault | \ErrorException $exception) {
            /*
             * Always reset the client. There may have been TCP connection issues, network issues,
             * or logic issues on Twinfield's side, it won't hurt to get a fresh connection.
             */
            $this->connection->resetClient(Services::PROCESSXML());

            /* For a given set of exception messages, always retry the request. */
            foreach (self::RETRY_REQUEST_EXCEPTION_MESSAGES as $message) {
                if (stripos($exception->getMessage(), $message) !== false) {
                    return $this->sendXmlDocument($document);
                }
            }

            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function getFinderService(): FinderService
    {
        return $this->connection->getAuthenticatedClient(Services::FINDER());
    }
}