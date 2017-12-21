<?php

namespace PhpTwinfield\Transactions;

use PhpTwinfield\Enums\LineType;

interface TransactionLine
{
    public function getType(): LineType;
    public function getId(): ?int;
}