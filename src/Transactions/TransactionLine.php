<?php

namespace PhpTwinfield\Transactions;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\ReferenceInterface;
use PhpTwinfield\Transactions\TransactionFields\LinesField;

interface TransactionLine
{
    /**
     * Get the type of line.
     *
     * @return LineType
     */
    public function getType(): LineType;

    /**
     * Get the id of the line (or null if not sent to Twinfield yet.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set the bank transaction on the line. This is needed later on.
     *
     * @param LinesField $object
     */
    public function setTransaction($object): void;

    /**
     * Gets the bank transaction from the line.
     *
     * Note that you should add the return type when implementing.
     *
     * @see ReferenceInterface
     * @return LinesField
     */
    public function getTransaction();
}