<?php

namespace PhpTwinfield;

interface ReferenceInterface
{
    public function getOffice(): Office;

    public function getCode(): string;

    public function getNumber(): string;

    public function getLineId(): int;
}