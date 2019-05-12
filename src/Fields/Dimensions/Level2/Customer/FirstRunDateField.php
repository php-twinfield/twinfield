<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * First run date field
 * Used by: CustomerCollectMandate
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait FirstRunDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $firstRunDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getFirstRunDate(): ?\DateTimeInterface
    {
        return $this->firstRunDate;
    }

    /**
     * @return string|null
     */
    public function getFirstRunDateToString(): ?string
    {
        if ($this->getFirstRunDate() != null) {
            return Util::formatDate($this->getFirstRunDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $firstRunDate
     * @return $this
     */
    public function setFirstRunDate(?\DateTimeInterface $firstRunDate)
    {
        $this->firstRunDate = $firstRunDate;
        return $this;
    }

    /**
     * @param string|null $firstRunDateString
     * @return $this
     * @throws Exception
     */
    public function setFirstRunDateFromString(?string $firstRunDateString)
    {
        if ((bool)strtotime($firstRunDateString)) {
            return $this->setFirstRunDate(Util::parseDate($firstRunDateString));
        } else {
            return $this->setFirstRunDate(null);
        }
    }
}