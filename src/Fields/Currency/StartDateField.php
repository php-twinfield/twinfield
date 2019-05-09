<?php

namespace PhpTwinfield\Fields\Currency;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Start date field
 * Used by: CurrencyRate
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait StartDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $startDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @return string|null
     */
    public function getStartDateToString(): ?string
    {
        if ($this->getStartDate() != null) {
            return Util::formatDate($this->getStartDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $startDate
     * @return $this
     */
    public function setStartDate(?\DateTimeInterface $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param string|null $startDateString
     * @return $this
     * @throws Exception
     */
    public function setStartDateFromString(?string $startDateString)
    {
        if ((bool)strtotime($startDateString)) {
            return $this->setStartDate(Util::parseDate($startDateString));
        } else {
            return $this->setStartDate(null);
        }
    }
}