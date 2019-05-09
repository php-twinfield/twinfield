<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Due date field
 * Used by: Invoice
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait DueDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $dueDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    /**
     * @return string|null
     */
    public function getDueDateToString(): ?string
    {
        if ($this->getDueDate() != null) {
            return Util::formatDate($this->getDueDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $dueDate
     * @return $this
     */
    public function setDueDate(?\DateTimeInterface $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @param string|null $dueDateString
     * @return $this
     * @throws Exception
     */
    public function setDueDateFromString(?string $dueDateString)
    {
        if ((bool)strtotime($dueDateString)) {
            return $this->setDueDate(Util::parseDate($dueDateString));
        } else {
            return $this->setDueDate(null);
        }
    }
}