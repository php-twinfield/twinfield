<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * @package PhpTwinfield\Transactions\TransactionLineFields
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
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDueDate(\DateTimeInterface $date)
    {
        $this->dueDate = $date;
        return $this;
    }

    /**
     * @param string $dateString
     * @return $this
     * @throws Exception
     */
    public function setDueDateFromString(string $dateString)
    {
        return $this->setDueDate(Util::parseDate($dateString));
    }
}
