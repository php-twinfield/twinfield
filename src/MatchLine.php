<?php

namespace PhpTwinfield;

use Money\Money;

class MatchLine
{
    /**
     * Daybook code (e.g. "CASH").
     *
     * @var string
     */
    private $transcode;

    /**
     * Transaction number.
     *
     * @var int
     */
    private $transnumber;

    /**
     * Transaction line number.
     *
     * @var int
     */
    private $transline;

    /**
     * @var Money|null
     */
    private $matchvalue;

    /**
     * @var Money|NULL
     */
    private $writeoff;

    /**
     * @var WriteOffType
     */
    private $writeofftype;

    /**
     * @return string
     */
    public function getTranscode(): string
    {
        return $this->transcode;
    }

    public function setTranscode(string $transcode): self
    {
        $this->transcode = $transcode;

        return $this;
    }

    public function getTransnumber(): int
    {
        return $this->transnumber;
    }

    public function setTransnumber(int $transnumber): self
    {
        $this->transnumber = $transnumber;

        return $this;
    }

    public function getTransline(): int
    {
        return $this->transline;
    }

    public function setTransline(int $transline): self
    {
        $this->transline = $transline;

        return $this;
    }

    public function getMatchValue(): ?Money
    {
        return $this->matchvalue;
    }

    /**
     * Optional; only for partial payments. Include an "-" on credit lines.
     *
     * @param Money $matchvalue
     * @return $this
     */
    public function setMatchvalue(?Money $matchvalue): self
    {
        $this->matchvalue = $matchvalue;

        return $this;
    }

    public function getWriteOff(): ?Money
    {
        return $this->writeoff;
    }

    /**
     * Optional; only for exchange rate differences, write-off or deduction. Include an "-" on credit lines.
     *
     * @param Money $amount
     * @param WriteOffType $type
     * @return $this
     */
    public function setWriteOff(Money $amount, WriteOffType $type)
    {
        $this->writeoff = $amount;
        $this->writeofftype = $type;

        return $this;
    }

    /**
     * Add the type attribute in order to determine what to do with the match difference.
     *
     * @return WriteOffType
     */
    public function getWriteOffType(): WriteOffType
    {
        return $this->writeofftype;
    }
}