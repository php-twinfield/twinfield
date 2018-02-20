<?php

namespace PhpTwinfield;

/**
 * The values provided by this interface uniquely define a booking (e.g. SalesTransaction, BankTransaction, etc.) in
 * Twinfield and can be used when a reference to the booking is needed, for example when deleting a booking.
 *
 * You can use the provided BookingReference object or you can implement this directly on one of your entities / domain
 * models.
 *
 * @see BookingReference
 */
interface BookingReferenceInterface
{
    /**
     * References the office in which the transaction was booked.
     */
    public function getOffice(): Office;

    /**
     * References the daybook on which the transaction was booked.
     */
    public function getCode(): string;

    /**
     * References the transaction.
     */
    public function getNumber(): int;
}