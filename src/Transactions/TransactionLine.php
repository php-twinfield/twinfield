<?php

namespace PhpTwinfield\Transactions;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\MatchReferenceInterface;

interface TransactionLine
{
    /**
     * Get the type of line.
     *
     * @return LineType
     */
    public function getLineType(): LineType;

    /**
     * Get the id of the line (or null if not sent to Twinfield yet.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get an object containing everything you need to uniquely reference this line.
     *
     * You can use this for matching.
     *
     * @return MatchReferenceInterface
     */
    public function getReference(): MatchReferenceInterface;
}
