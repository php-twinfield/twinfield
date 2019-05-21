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
    private $transCode;

    /**
     * Transaction number.
     *
     * @var int
     */
    private $transNumber;

    /**
     * Transaction line number.
     *
     * @var int
     */
    private $transLine;

    /**
     * @var Money|null
     */
    private $matchValue;

    /**
     * @var Money|null
     */
    private $writeOff;

    /**
     * @var Enums\WriteOffType|null
     */
    private $writeOffType;

    /**
     * Create a new matchline based on a MatchReferenceInterface.
     *
     * If you want to add a write off, add it manually with setWriteOff(). Pass $value for partial matching.
     * @see setWriteOff()
     */
    public static function addToMatchSet(MatchSet $set, MatchReferenceInterface $reference, Money $value = null): self
    {
        Assert::eq($set->getOffice(), $reference->getOffice());

        $instance = new self;
        $instance->transCode   = $reference->getCode();
        $instance->transNumber = $reference->getNumber();
        $instance->transLine   = $reference->getLineId();
        $instance->setMatchValue($value);

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
    public function getTransCode(): string
    {
        return $this->transCode;
    }

    public function getTransNumber(): int
    {
        return $this->transNumber;
    }

    public function getTransLine(): int
    {
        return $this->transLine;
    }

    public function getMatchValue(): ?Money
    {
        return $this->matchValue;
    }

    /**
     * Optional; only for partial payments. Include an "-" on credit lines.
     */
    public function setMatchValue(?Money $matchValue): self
    {
        $this->matchValue = $matchValue;

        return $this;
    }

    public function getWriteOff(): ?Money
    {
        return $this->writeOff;
    }

    /**
     * Optional; only for exchange rate differences, write-off or deduction. Include an "-" on credit lines.
     */
    public function setWriteOff(Money $amount, Enums\WriteOffType $type): self
    {
        $this->writeOff = $amount;
        $this->writeOffType = $type;

        return $this;
    }

    /**
     * Add the type attribute in order to determine what to do with the match difference.
     */
    public function getWriteOffType(): ?Enums\WriteOffType
    {
        return $this->writeOffType;
    }
}