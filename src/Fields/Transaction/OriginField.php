<?php

namespace PhpTwinfield\Fields\Transaction;

trait OriginField
{
    /**
     * Origin field
     * Used by: BaseTransaction
     *
     * @var string|null
     */
    private $origin;

    /**
     * @return null|string
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param null|string $origin
     * @return $this
     */
    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;
        return $this;
    }
}