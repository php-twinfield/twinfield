<?php

namespace PhpTwinfield;

final class MatchReference implements MatchReferenceInterface
{
    /**
     * @var BookingReferenceInterface
     */
    private $bookingReference;

    /**
     * @var int
     */
    private $lineId;

    public static function fromBookingReference(
        BookingReferenceInterface $bookingReference,
        int $lineId
    ): MatchReferenceInterface {
        return new self(
            $bookingReference->getOffice(),
            $bookingReference->getCode(),
            $bookingReference->getNumber(),
            $lineId
        );
    }

    public function __construct(Office $office, string $code, int $number, int $lineId)
    {
        $this->bookingReference = new BookingReference($office, $code, $number);
        $this->lineId = $lineId;
    }

    public function getOffice(): Office
    {
        return $this->bookingReference->getOffice();
    }

    public function getCode(): string
    {
        return $this->bookingReference->getCode();
    }

    public function getNumber(): int
    {
        return $this->bookingReference->getNumber();
    }

    public function getLineId(): int
    {
        return $this->lineId;
    }
}