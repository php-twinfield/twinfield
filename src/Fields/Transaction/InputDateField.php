<?php

namespace PhpTwinfield\Fields\Transaction;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Input date field
 * Used by: BaseTransaction
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
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
     * @return string|null
     */
    public function getInputDateToString(): ?string
    {
        if ($this->getInputDate() != null) {
            return Util::formatDateTime($this->getInputDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $inputDateString
     * @return $this
     * @throws Exception
     */
    public function setInputDateFromString(?string $inputDateString)
    {
        if ((bool)strtotime($inputDateString)) {
            return $this->setInputDate(Util::parseDateTime($inputDateString));
        } else {
            return $this->setInputDate(null);
        }
    }
}