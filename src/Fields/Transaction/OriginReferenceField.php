<?php

namespace PhpTwinfield\Fields\Transaction;

trait OriginReferenceField
{
    /**
     * Origin reference field
     * Used by: BaseTransaction
     *
     * @var string|null
     */
    private $originReference;

    /**
     * @return null|string
     */
    public function getOriginReference(): ?string
    {
        return $this->originReference;
    }

    /**
     * @param null|string $originReference
     * @return $this
     */
    public function setOriginReference(?string $originReference): self
    {
        $this->originReference = $originReference;
        return $this;
    }
}
