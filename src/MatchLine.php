<?php

namespace PhpTwinfield;

use Money\Money;
use Webmozart\Assert\Assert;

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
     * @var Enums\WriteOffType
     */
    private $writeofftype;

    /**
     * Create a new matchline based on a MatchReferenceInterface.
     *
     * If you want to add a write off, add it manually with setWriteOff()
     *
     * @param MatchSet $set
     * @param MatchReferenceInterface $reference
     * @param Money|null $value Use for partial matching.
     * @return MatchLine
     * @see setWriteOff()
     */
    public static function addToMatchSet(MatchSet $set, MatchReferenceInterface $reference, Money $value = null): self
    {
        Assert::eq($set->getOffice(), $reference->getOffice());

        $instance = new self;
        $instance->transcode   = $reference->getCode();
        $instance->transnumber = $reference->getNumber();
        $instance->transline   = $reference->getLineId();
        $instance->setMatchvalue($value);

        $set->addLine($instance);

        return $instance;
    }

    /**
     * MatchLine constructor.
     * @see addToMatchSet
     */
    private function __construct() {}

    /**
     * @return string
     */
    public function getTranscode(): string
    {
        return $this->transcode;
    }

    public function getTransnumber(): int
    {
        return $this->transnumber;
    }

    public function getTransline(): int
    {
        return $this->transline;
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
     * @param Enums\WriteOffType $type
     * @return $this
     */
    public function setWriteOff(Money $amount, Enums\WriteOffType $type)
    {
        $this->writeoff = $amount;
        $this->writeofftype = $type;

        return $this;
    }

    /**
     * Add the type attribute in order to determine what to do with the match difference.
     *
     * @return Enums\WriteOffType
     */
    public function getWriteOffType(): Enums\WriteOffType
    {
        return $this->writeofftype;
    }
}