<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\BaseTransaction;

trait DueDateField
{
    /**
     * @var string The due date in 'YYYYMMDD' format.
     */
    private $dueDate;

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    public function setDueDate(string $dueDate): BaseTransaction
    {
        $this->dueDate = $dueDate;

        return $this;
    }
}
