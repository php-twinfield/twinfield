<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Modified field
 * Used by: AssetMethod, Office, User, VatCode
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
 */
trait ModifiedField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $modified;

    /**
     * @return \DateTimeInterface|null
     */
    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * @return string|null
     */
    public function getModifiedToString(): ?string
    {
        if ($this->getModified() != null) {
            return Util::formatDateTime($this->getModified());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $modified
     * @return $this
     */
    public function setModified(?\DateTimeInterface $modified)
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * @param string|null $modifiedString
     * @return $this
     * @throws Exception
     */
    public function setModifiedFromString(?string $modifiedString)
    {
        if ((bool)strtotime($modifiedString)) {
            return $this->setModified(Util::parseDateTime($modifiedString));
        } else {
            return $this->setModified(null);
        }
    }
}