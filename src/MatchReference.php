<?php

namespace PhpTwinfield;

final class MatchReference implements MatchReferenceInterface
{
    /**
     * @var Office
     */
    private $office;

    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $lineId;

    public function __construct(Office $office, string $code, int $number, int $lineId)
    {
        $this->office = $office;
        $this->code   = $code;
        $this->number = $number;
        $this->lineId = $lineId;
    }

    public function getOffice(): Office
    {
        return $this->office;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getLineId(): int
    {
        return $this->lineId;
    }
}