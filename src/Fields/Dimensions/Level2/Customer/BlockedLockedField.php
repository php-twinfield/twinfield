<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait BlockedLockedField
{
    /**
     * Blocked locked field
     * Used by: CustomerCreditManagement
     *
     * @var bool
     */
    private $blockedLocked;

    /**
     * @return bool
     */
    public function getBlockedLocked(): ?bool
    {
        return $this->blockedLocked;
    }

    /**
     * @param bool $blockedLocked
     * @return $this
     */
    public function setBlockedLocked(?bool $blockedLocked): self
    {
        $this->blockedLocked = $blockedLocked;
        return $this;
    }
}
