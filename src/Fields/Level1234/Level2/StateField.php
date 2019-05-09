<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait StateField
{
    /**
     * State field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $state;

    /**
     * @return null|string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param null|string $state
     * @return $this
     */
    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }
}