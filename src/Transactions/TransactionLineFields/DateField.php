<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * @package PhpTwinfield\Transactions\TransactionLineFields
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
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $dateString
     * @return $this
     * @throws Exception
     */
    public function setDateFromString(string $dateString)
    {
        return $this->setDate(Util::parseDate($dateString));
    }
}