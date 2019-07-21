<?php

namespace PhpTwinfield\Fields\Transaction;

/**
 * Input date field
 * Used by: BaseTransaction
 *
 * @package PhpTwinfield\Traits
 */
trait InputDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $inputDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getInputDate(): ?\DateTimeInterface
    {
        return $this->inputDate;
    }

    /**
     * @param \DateTimeInterface|null $inputDate
     * @return $this
     */
    public function setInputDate(?\DateTimeInterface $inputDate)
    {
        $this->inputDate = $inputDate;
        return $this;
    }
}
