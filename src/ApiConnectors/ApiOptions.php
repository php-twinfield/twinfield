<?php

namespace PhpTwinfield\ApiConnectors;

final class ApiOptions
{
    private $retriableExceptionMessages = [
        "SSL: Connection reset by peer",
        "Your logon credentials are not valid anymore. Try to log on again."
    ];

    private $maxRetries = 3;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(array $messages = null, int $maxRetries = null)
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
    private function validateMaxRetries(int $maxRetries)
    {
        if ($maxRetries < 0) {
            throw new \InvalidArgumentException('The max retries should be a positive integer.');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            if (trim($message) === '') {
                throw new \InvalidArgumentException(
                    sprintf('The exception message should not be empty. Key: [%s]', $key)
                );
            }
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