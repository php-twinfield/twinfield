<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Date field
 * Used by: BaseTransaction, ElectronicBankStatement, VatCodePercentage
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait DateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $date;

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return string|null
     */
    public function getDateToString(): ?string
    {
        if ($this->getDate() != null) {
            return Util::formatDate($this->getDate());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $date
     * @return $this
     */
    public function setDate(?\DateTimeInterface $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string|null $dateString
     * @return $this
     * @throws Exception
     */
    public function setDateFromString(?string $dateString)
    {
        if ((bool)strtotime($dateString)) {
            return $this->setDate(Util::parseDate($dateString));
        } else {
            return $this->setDate(null);
        }
    }
}