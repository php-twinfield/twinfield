<?php

namespace PhpTwinfield\Fields\Rate;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * End date field
 * Used by: RateRateChange
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait EndDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $endDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @return string|null
     */
    public function getEndDateToString(): ?string
    {
        if ($this->getEndDate() != null) {
            return Util::formatDate($this->getEndDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $endDate
     * @return $this
     */
    public function setEndDate(?\DateTimeInterface $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @param string|null $endDateString
     * @return $this
     * @throws Exception
     */
    public function setEndDateFromString(?string $endDateString)
    {
        if ((bool)strtotime($endDateString)) {
            return $this->setEndDate(Util::parseDate($endDateString));
        } else {
            return $this->setEndDate(null);
        }
    }
}