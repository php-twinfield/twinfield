<?php

namespace PhpTwinfield\Fields\Transaction;

trait NumberField
{
    /**
     * Number field
     * Used by: BaseTransaction
     *
     * @var int|null
     */
    private $number;

    /**
     * @return null|int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param null|int $number
     * @return $this
     */
    public function setNumber(?int $number): self
    {
        $this->number = $number;
        return $this;
    }
}
