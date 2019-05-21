<?php

namespace PhpTwinfield\Fields\Transaction;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * ModificationDate field
 * Used by: BaseTransaction
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
 */
trait ModificationDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $modificationDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    /**
     * @return string|null
     */
    public function getModificationDateToString(): ?string
    {
        if ($this->getModificationDate() != null) {
            return Util::formatDateTime($this->getModificationDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $modificationDate
     * @return $this
     */
    public function setModificationDate(?\DateTimeInterface $modificationDate)
    {
        $this->modificationDate = $modificationDate;
        return $this;
    }

    /**
     * @param string|null $modificationDateString
     * @return $this
     * @throws Exception
     */
    public function setModificationDateFromString(?string $modificationDateString)
    {
        if ((bool)strtotime($modificationDateString)) {
            return $this->setModificationDate(Util::parseDateTime($modificationDateString));
        } else {
            return $this->setModificationDate(null);
        }
    }
}