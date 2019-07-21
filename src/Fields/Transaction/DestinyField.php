<?php

namespace PhpTwinfield\Fields\Transaction;

use PhpTwinfield\Enums\Destiny;

trait DestinyField
{
    /**
     * Destiny field
     * Used by: BaseTransaction
     *
     * @var Destiny|null
     */
    private $destiny;

    public function getDestiny(): ?Destiny
    {
        return $this->destiny;
    }

    /**
     * @param Destiny|null $destiny
     * @return $this
     */
    public function setDestiny(?Destiny $destiny): self
    {
        $this->destiny = $destiny;
        return $this;
    }
}
