<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;
use PhpTwinfield\Util;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

abstract class BaseApiConnector implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Make sure to only add error messages for failure cases that caused the server not to accept / receive the
     * request. Else the automatic retry will cause the request to be understood by the server twice.
     *
     * @var string[]
     */
    private const RETRY_REQUEST_EXCEPTION_MESSAGES = [
        "SSL: Connection reset by peer",
        "Your logon credentials are not valid anymore. Try to log on again."
    ];

    /**
     * @var int
     */
    private const MAX_RETRIES = 3;

    /**
     * @var AuthenticatedConnection
     */
    private $connection;

    /**
     * @var int
     */
    private $numRetries = 0;

    /**
     * @var mixed
     */
    private $config;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthenticatedConnection $connection, array $configuration = [])
    {
        $this->connection = $connection;

        $this->config[ApiOptions::RETRIABLE_EXCEPTION_MESSAGES] = ApiOptions::RETRIABLE_EXCEPTION_MESSAGES;
        $this->config[ApiOptions::CONFIG_MAX_RETRIES] = self::MAX_RETRIES;

        $this->setConfig($configuration);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function setConfig(array $configuration): void
    {
        if (array_key_exists(ApiOptions::CONFIG_MAX_RETRIES, $configuration)) {
            if ($configuration[ApiOptions::CONFIG_MAX_RETRIES] < 0) {
                throw new \InvalidArgumentException(
                    'The CONFIG_MAX_RETRIES configuration cannot be less than zero.'
                );
            }
            $this->config[ApiOptions::CONFIG_MAX_RETRIES] = $configuration[ApiOptions::CONFIG_MAX_RETRIES];
        }

        if (empty($configuration[ApiOptions::RETRIABLE_EXCEPTION_MESSAGES])) {
            $error_messages = self::RETRY_REQUEST_EXCEPTION_MESSAGES;
        } else {
            $error_messages = $configuration[ApiOptions::RETRIABLE_EXCEPTION_MESSAGES];
        }

        if (!empty($configuration[ApiOptions::APPEND_RETRIABLE_EXCEPTION_MESSAGES])) {
            $error_messages = array_merge(
                $error_messages,
                $configuration[ApiOptions::APPEND_RETRIABLE_EXCEPTION_MESSAGES]
            );
        }

        $this->config[ApiOptions::CONFIG_EXCEPTION_MESSAGES] = array_filter(
            $error_messages,
            static function(string $message):bool {
                return trim($message) !== '';
            }
        );
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \RuntimeException
     */
    public function getConfig(string $key)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new \RuntimeException(
                sprintf('The configuration for the key: [%s] was not set.', $key)
            );
        }
        return $this->config[$key];
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
            foreach ($this->getConfig(ApiOptions::CONFIG_EXCEPTION_MESSAGES) as $message) {
                if (stripos($exception->getMessage(), $message) === false) {
                    continue;
                }
                $this->numRetries++;

                if ($this->numRetries > $this->getConfig(ApiOptions::CONFIG_MAX_RETRIES)) {
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
}
