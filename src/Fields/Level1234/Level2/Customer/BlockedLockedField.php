<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

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

    public function getBlockedLockedToString(): ?string
    {
        return ($this->getBlockedLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $blockedLockedString
     * @return $this
     * @throws Exception
     */
    public function setBlockedLockedFromString(?string $blockedLockedString)
    {
        return $this->setBlockedLocked(filter_var($blockedLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}