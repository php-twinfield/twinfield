<?php

namespace PhpTwinfield;


/**
 * The values provided by this interface uniquely define a transaction line in Twinfield and are
 * used in the Matching API.
 *
 * You can use the provided MatchReference object or you can implement this directly on one of your entities / domain
 * models.
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
interface MatchReferenceInterface
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

    /**
     * Reference the exact line in the transaction.
     */
    public function getLineId(): int;
}