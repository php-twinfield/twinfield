<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Webmozart\Assert\Assert;

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
        Assert::length($freeChar, 1);
        $this->freeChar = $freeChar;

        return $this;
    }
}
