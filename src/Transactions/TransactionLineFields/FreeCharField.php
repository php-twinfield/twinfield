<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

trait FreeCharField
{
    /**
     * If line type is total and filled with N the sales invoice is excluded from direct debit runs done in Twinfield.
     *
     * @var string|null
     */
    private $freeChar;

    /**
     * @return string|null
     */
    public function getFreeChar(): ?string
    {
        return $this->freeChar;
    }

    /**
     * @param string|null $freeChar
     * @return $this
     */
    public function setFreeChar(?string $freeChar): self
    {
        $this->freeChar = $freeChar;

        return $this;
    }
}
