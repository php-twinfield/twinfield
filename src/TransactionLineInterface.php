<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\MatchReferenceInterface;

interface TransactionLineInterface
{
    /**
     * Set the transaction on the line. This is needed later on.
     *
     * @param $object
     * @throws \InvalidArgumentException If a transaction is invalid or if a transaction is already set.
     * @internal
     */
    public function setTransaction($object): void;

    /**
     * Gets the transaction from the line.
     *
     * Note that you should add the return type when implementing.
     *
     * @see MatchReferenceInterface
     * @internal
     */
    public function getTransaction();

    /**
     * Get an object containing everything you need to uniquely reference this line.
     *
     * You can use this for matching.
     *
     * @return MatchReferenceInterface
     */
    public function getReference(): MatchReferenceInterface;
}