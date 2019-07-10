<?php

namespace PhpTwinfield\ApiConnectors;

/**
 * Class ApiOptions
 * @package PhpTwinfield\ApiConnectors
 */
final class ApiOptions
{
    /**
     * @var string[] exception messages that should be retried
     */
    private $retriableExceptionMessages = [
        "SSL: Connection reset by peer",
        "Your logon credentials are not valid anymore. Try to log on again."
    ];
    private $maxRetries = 3;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(?array $messages = null, ?int $maxRetries = null)
    {
        if ($messages !== null) {
            $this->validateMessages($messages);
            $this->retriableExceptionMessages = $messages;
        }
        if ($maxRetries !== null) {
            $this->validateMaxRetries($maxRetries);
            $this->maxRetries = $maxRetries;
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateMaxRetries(int $maxRetries): void
    {
        if ($maxRetries < 0) {
            throw new \InvalidArgumentException('The max retries should be a positive integer.');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateMessages(array $messages): void
    {
        $exceptions = [];
        array_walk(
            $messages,
            static function(string $message, string $key):void
            {
                if (trim($message) === '') {
                    $exceptions[] = sprintf('The exception message should not be empty. Key: [%s]', $key);
                }
            }
        );
        if (count($exceptions)) {
            throw new \InvalidArgumentException(implode(PHP_EOL, $exceptions));
        }
    }

    /**
     * @return array
     */
    public function getRetriableExceptionMessages(): array
    {
        return $this->retriableExceptionMessages;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setRetriableExceptionMessages(array $retriableExceptionMessages): ApiOptions
    {
        return new self(
            $retriableExceptionMessages,
            $this->maxRetries
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function addMessages(array $messages): ApiOptions
    {
        return new self(
            array_merge($messages, $this->retriableExceptionMessages),
            $this->maxRetries
        );
    }

    /**
     * @return int
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setMaxRetries(int $maxRetries): ApiOptions
    {
        return new self(
            $this->retriableExceptionMessages,
            $maxRetries
        );
    }

}