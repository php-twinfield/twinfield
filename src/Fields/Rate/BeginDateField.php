<?php

namespace PhpTwinfield\Fields\Rate;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Begin date field
 * Used by: RateRateChange
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait BeginDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $beginDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    /**
     * @return string|null
     */
    public function getBeginDateToString(): ?string
    {
        if ($this->getBeginDate() != null) {
            return Util::formatDate($this->getBeginDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $beginDate
     * @return $this
     */
    public function setBeginDate(?\DateTimeInterface $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * @param string|null $beginDateString
     * @return $this
     * @throws Exception
     */
    public function setBeginDateFromString(?string $beginDateString)
    {
        if ((bool)strtotime($beginDateString)) {
            return $this->setBeginDate(Util::parseDate($beginDateString));
        } else {
            return $this->setBeginDate(null);
        }
    }
}