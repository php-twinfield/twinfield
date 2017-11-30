<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait DueDateField
{
    /**
     * @var string|null The due date in 'YYYYMMDD' format.
     */
    private $dueDate;

    /**
     * @return string|null
     */
    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    /**
     * @param string|null $dueDate
     * @return $this
     */
    public function setDueDate(?string $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }
}
