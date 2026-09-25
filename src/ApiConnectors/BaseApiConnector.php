<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Response\HeaderBag;
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
     * @var int
     */
    private $retryDelay = 0;

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
        $service = null;

        try {
            $service = $this->getProcessXmlService();
            $response = $service->sendDocument($document);
            $this->numRetries = 0;

            $this->logResponse($response);

            return $response;
        } catch (\SoapFault | \ErrorException $exception) {
            /* Set retry delay from client's last response header before reset */
            if ($service !== null && $this->getOptions()->getUseRetryAfterHeader()) {
                $this->setRetryDelayFromHeaders($service->__getLastResponseHeaders());
            }

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

                if ($this->retryDelay > 0) {
                    $this->logSleep();
                    $this->sleep();
                }

                $this->logRetry($exception);
                return $this->sendXmlDocument($document);
            }

            $this->numRetries = 0;
            $this->logFailedRequest($exception);
            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * Run a search against the Twinfield Finder service.
     *
     * Uses the same retry behaviour as sendXmlDocument(): retriable exception messages from the ApiOptions are retried
     * up to the configured maximum, and when the Retry-After header is enabled (e.g. on a 429 "Too Many Requests") the
     * connector waits the number of seconds Twinfield asks for before retrying.
     *
     * @param string $type     One of the FinderService::TYPE_* constants.
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    One of the FinderService::SEARCH_FIELD_* constants.
     * @param int    $firstRow First row to return (1-based), useful for paging.
     * @param int    $maxRows  Maximum number of rows to return, useful for paging.
     * @param array  $options  The Finder options.
     * @return \stdClass
     * @throws Exception
     */
    protected function searchFinder(
        string $type,
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): \stdClass {
        $numRetries = 0;

        while (true) {
            $service = null;

            try {
                $service = $this->getFinderService();

                return $service->searchFinder($type, $pattern, $field, $firstRow, $maxRows, $options);
            } catch (\SoapFault | \ErrorException $exception) {
                if ($service !== null && $this->getOptions()->getUseRetryAfterHeader()) {
                    $this->setRetryDelayFromHeaders($service->__getLastResponseHeaders());
                }

                $this->connection->resetClient(Services::FINDER());

                if (!$this->isRetriable($exception) || ++$numRetries > $this->getOptions()->getMaxRetries()) {
                    $this->logFailedRequest($exception);
                    throw new Exception($exception->getMessage(), 0, $exception);
                }

                if ($this->retryDelay > 0) {
                    $this->logSleep();
                    $this->sleep();
                }

                $this->logRetry($exception);
            }
        }
    }

    private function isRetriable(\Throwable $exception): bool
    {
        foreach ($this->getOptions()->getRetriableExceptionMessages() as $message) {
            if (stripos($exception->getMessage(), $message) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalise the rows of a Finder response to a list of string arrays.
     *
     * ext-soap returns a single row as an object instead of a list, and each row may either be a plain array or an
     * object with a "string" property, depending on how the response was decoded.
     *
     * @return string[][]
     */
    protected static function getFinderRows(\stdClass $response): array
    {
        if (!isset($response->data) || (int) ($response->data->TotalRows ?? 0) === 0 || !isset($response->data->Items->ArrayOfString)) {
            return [];
        }

        $items = $response->data->Items->ArrayOfString;
        if (!is_array($items)) {
            $items = [$items];
        }

        $rows = [];
        foreach ($items as $item) {
            if (is_object($item)) {
                $item = $item->string ?? [];
            }

            $rows[] = array_values((array) $item);
        }

        return $rows;
    }

    /**
     * @param string|null $rawHeaders
     * @return void
     */
    public function setRetryDelayFromHeaders(?string $rawHeaders): void
    {
        $headers = HeaderBag::fromString($rawHeaders ?? '');
        $this->retryDelay = (int)$headers->get("Retry-After", "0");
    }

    final protected function unwrapSingleResponse(MappedResponseCollection $responses) {
        Assert::count($responses, 1);

        return $responses->getIterator()->current()->unwrap();
    }

    /**
     * Indirection for sleep to allow mocking in tests.
     */
    protected function sleep(): void
    {
        sleep($this->retryDelay);
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

    private function logSleep(): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info("Waiting {$this->retryDelay} seconds before retrying request.");
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
