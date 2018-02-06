<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;

abstract class BaseApiConnector
{
    /**
     * @var AuthenticatedConnection
     */
    private $connection;

    public function __construct(AuthenticatedConnection $connection)
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
        } catch (\SoapFault $soapFault) {
            /*
             * Always reset the client. There may have been TCP connection issues, network issues, or logic issues on
             * Twinfield's side, it won't hurt to get a fresh connection.
             */
            $this->connection->resetClient(Services::PROCESSXML());

            if (stripos($soapFault->getMessage(), "Your logon credentials are not valid anymore. Try to log on again.") !== false) {
                /*
                 * Automatically retry and log on again.
                 */
                return $this->sendXmlDocument($document);
            }

            throw new Exception($soapFault->getMessage(), 0, $soapFault);
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
