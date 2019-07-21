<?php

namespace PhpTwinfield\Fields;

/**
 * Due date field
 * Used by: Invoice, PurchaseTransaction, SalesTransaction
 *
 * @package PhpTwinfield\Traits
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
     * @param \DateTimeInterface|null $dueDate
     * @return $this
     */
    public function setDueDate(?\DateTimeInterface $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }
}
