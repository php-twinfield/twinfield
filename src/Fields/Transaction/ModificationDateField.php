<?php

namespace PhpTwinfield\Fields\Transaction;

/**
 * ModificationDate field
 * Used by: BaseTransaction
 *
 * @package PhpTwinfield\Traits
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
     * @param \DateTimeInterface|null $modificationDate
     * @return $this
     */
    public function setModificationDate(?\DateTimeInterface $modificationDate)
    {
        $this->modificationDate = $modificationDate;
        return $this;
    }
}