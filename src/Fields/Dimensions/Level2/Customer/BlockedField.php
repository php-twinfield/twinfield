<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait BlockedField
{
    /**
     * Blocked field
     * Used by: CustomerCreditManagement
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
