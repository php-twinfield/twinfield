<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait BankBlockedField
{
    /**
     * Bank blocked field
     * Used by: CustomerBank, SupplierBank
     *
     * @var bool
     */
    private $blocked;

    /**
     * @return bool
     */
    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     * @return $this
     */
    public function setBlocked(?bool $blocked): self
    {
        $this->blocked = $blocked;
        return $this;
    }
}
