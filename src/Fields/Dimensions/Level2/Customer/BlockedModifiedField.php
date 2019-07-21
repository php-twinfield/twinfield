<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

/**
 * Blocked modified field
 * Used by: CustomerCreditManagement
 *
 * @package PhpTwinfield\Traits
 */
trait BlockedModifiedField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $blockedModified;

    /**
     * @return \DateTimeInterface|null
     */
    public function getBlockedModified(): ?\DateTimeInterface
    {
        return $this->blockedModified;
    }

    /**
     * @param \DateTimeInterface|null $blockedModified
     * @return $this
     */
    public function setBlockedModified(?\DateTimeInterface $blockedModified)
    {
        $this->blockedModified = $blockedModified;
        return $this;
    }
}
