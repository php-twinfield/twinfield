<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use PhpTwinfield\BookingReference;
use PhpTwinfield\MatchReferenceInterface;

/**
 * @see MatchReferenceInterface
 */
trait CodeNumberOfficeFields
{
    /*
     * Traits using traits, when will the madness end.
     */
    use OfficeField;

    /**
     * @var string
     */
    private $code;

    /**
     * @var int|null
     */
    private $number;

    /**
     * Transaction type code.
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * When creating a new transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a transaction, the related transaction number should be provided.
     *
     * @return int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * Get the booking reference. The booking reference uniquely identifies a booking.
     *
     * @return BookingReference
     */
    public function getBookingReference(): BookingReference
    {
        return new BookingReference(
            $this->office,
            $this->code,
            $this->number
        );
    }
}