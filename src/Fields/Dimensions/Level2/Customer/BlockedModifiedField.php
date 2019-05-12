<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Blocked modified field
 * Used by: CustomerCreditManagement
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
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
     * @return string|null
     */
    public function getBlockedModifiedToString(): ?string
    {
        if ($this->getBlockedModified() != null) {
            return Util::formatDateTime($this->getBlockedModified());
        } else {
            return null;
        }
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

    /**
     * @param string|null $blockedModifiedString
     * @return $this
     * @throws Exception
     */
    public function setBlockedModifiedFromString(?string $blockedModifiedString)
    {
        if ((bool)strtotime($blockedModifiedString)) {
            return $this->setBlockedModified(Util::parseDateTime($blockedModifiedString));
        } else {
            return $this->setBlockedModified(null);
        }
    }
}