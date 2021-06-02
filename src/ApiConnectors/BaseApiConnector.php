<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;
use PhpTwinfield\Services\SelectOfficeService;
use PhpTwinfield\Services\SessionService;
use PhpTwinfield\Util;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Webmozart\Assert\Assert;

abstract class BaseApiConnector implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var AuthenticatedConnection
     */
    private $connection;

    /**
     * @var int
     */
    private $numRetries = 0;

    /**
     * @var ApiOptions
     */
    private $options;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthenticatedConnection $connection, ?ApiOptions $options = null)
    {
        $this->connection = $connection;
        if ($options === null) {
            $this->options = new ApiOptions();
        } else {
            $this->options = $options;
        }
    }

    public function getOptions(): ApiOptions
    {
        return $this->options;
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
     * @throws \RuntimeException
     */
    public function sendXmlDocument(\DOMDocument $document) {
        $this->logSendingDocument($document);

        try {
            $response = $this->getProcessXmlService()->sendDocument($document);
            $this->numRetries = 0;

            $this->logResponse($response);

            return $response;
        } catch (\SoapFault | \ErrorException $exception) {
            /*
             * Always reset the client. There may have been TCP connection issues, network issues,
             * or logic issues on Twinfield's side, it won't hurt to get a fresh connection.
             */
            $this->connection->resetClient(Services::PROCESSXML());

            /* For a given set of exception messages, always retry the request. */
            foreach ($this->getOptions()->getRetriableExceptionMessages() as $message) {
                if (stripos($exception->getMessage(), $message) === false) {
                    continue;
                }
                $this->numRetries++;

                if ($this->numRetries > $this->getOptions()->getMaxRetries()) {
                    break;
                }

                $this->logRetry($exception);
                return $this->sendXmlDocument($document);
            }

            $this->numRetries = 0;
            $this->logFailedRequest($exception);
            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }

    final protected function unwrapSingleResponse(MappedResponseCollection $responses) {
        Assert::count($responses, 1);

        return $responses->getIterator()->current()->unwrap();
    }

    private function logSendingDocument(\DOMDocument $document): void
    {
        if (!$this->logger) {
            return;
        }

        $message = "Sending request to Twinfield.";
        if ($this->numRetries > 0) {
            $message .= ' (attempt ' . ($this->numRetries + 1) . ')';
        }

        $this->logger->debug(
            $message,
            [
                'document_xml' => Util::getPrettyXml($document),
            ]
        );
    }

    private function logResponse(Response $response): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->debug(
            "Received response from Twinfield.",
            [
                'document_xml' => Util::getPrettyXml($response->getResponseDocument()),
            ]
        );
    }

    private function logRetry(\Throwable $e): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info("Retrying request. Reason for initial failure: {$e->getMessage()}");
    }

    private function logFailedRequest(\Throwable $e): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->error("Request to Twinfield failed: {$e->getMessage()}");
    }

    /**
     * @throws Exception
     */
    protected function getFinderService(): FinderService
    {
        return $this->connection->getAuthenticatedClient(Services::FINDER());
    }

    /**
     * @throws Exception
     */
    protected function getSessionService(): SessionService
    {
        return $this->connection->getAuthenticatedClient(Services::SESSION());
    }
}
