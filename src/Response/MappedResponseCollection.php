<?php

namespace PhpTwinfield\Response;

use Webmozart\Assert\Assert;

class MappedResponseCollection extends \ArrayObject
{
    /**
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     */
    public function append($value): void
    {
        if (!($value instanceof IndividualMappedResponse)) {
            throw new \InvalidArgumentException("Value has to be an object of type " . IndividualMappedResponse::class);
        }
        parent::append($value);
    }

    public function hasSuccessfulResponses(): bool
    {
        return $this->countResponses(true) > 0;
    }

    public function countSuccessfulResponses(): int
    {
        return $this->countResponses(true);
    }

    /**
     * @return IndividualMappedResponse[]
     */
    public function getSuccessfulResponses(): array
    {
        return $this->getResponses(true);
    }

    public function hasFailedResponses(): bool
    {
        return $this->countResponses(false) > 0;
    }

    public function countFailedResponses(): int
    {
        return $this->countResponses(false);
    }

    /**
     * @return IndividualMappedResponse[]
     */
    public function getFailedResponses(): array
    {
        return $this->getResponses(false);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function assertAllSuccessful(): void
    {
        Assert::eq($this->countResponses(false), 0);
    }

    /**
     * @var bool $successful
     *
     * @return IndividualMappedResponse[]
     */
    private function getResponses(bool $successful): array
    {
        $responses = [];

        /** @var IndividualMappedResponse $response */
        foreach ($this as $response) {
            if ($response->isSuccessful() === $successful) {
                $responses[] = $response;
            }
        }

        return $responses;
    }

    private function countResponses(bool $successful): int
    {
        $count = 0;

        /** @var IndividualMappedResponse $response */
        foreach ($this as $response) {
            if ($response->isSuccessful() === $successful) {
                $count++;
            }
        }

        return $count;
    }
}
