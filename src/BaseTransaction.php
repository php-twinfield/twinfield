<?php

namespace PhpTwinfield;

/**
 * @todo $modificationDate The date/time on which the sales transaction was modified the last time. Read-only attribute.
 * @todo $user The user who created the sales transaction. Read-only attribute.
 * @todo $inputDate The date/time on which the transaction was created. Read-only attribute.
 */
abstract class BaseTransaction extends BaseObject
{
    const DESTINY_TEMPORARY = 'temporary'; // Also called 'provisional'
    const DESTINY_FINAL     = 'final';

    /**
     * @var string|null Either self::DESTINY_TEMPORARY or self::DESTINY_FINAL.
     */
    private $destiny;

    /**
     * @var bool|null Should VAT be rounded or not? Rounding will only be done with a maximum of two cents.
     */
    private $autoBalanceVat;

    /**
     * @var bool|null Should warnings be given or not?
     */
    private $raiseWarning;

    /**
     * @var string|null The office code.
     */
    private $office;

    /**
     * @var string|null The transaction type code.
     */
    private $code;

    /**
     * @var int|null The transaction number.
     */
    private $number;

    /**
     * @var string|null Period in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left empty,
     *                  the period is determined by the system based on the provided transaction date.
     */
    private $period;

    /**
     * @var string|null The currency code.
     */
    private $currency;

    /**
     * @var string|null The date in 'YYYYMMDD' format.
     */
    private $date;

    /**
     * @var string|null The sales transaction origin. Read-only attribute.
     */
    private $origin;

    /**
     * @var string|null
     */
    private $freetext1;

    /**
     * @var string|null
     */
    private $freetext2;

    /**
     * @var string|null
     */
    private $freetext3;

    /**
     * @var BaseTransactionLine[]
     */
    private $lines = [];

    /**
     * @return string The class name for transaction lines supported by this transaction. Must be a sub class of
     *                BaseTransactionLine.
     */
    abstract public function getLineClassName(): string;

    public function getDestiny(): ?string
    {
        return $this->destiny;
    }

    public function setDestiny(?string $destiny): self
    {
        $this->destiny = $destiny;

        return $this;
    }

    public function getAutoBalanceVat(): ?bool
    {
        return $this->autoBalanceVat;
    }

    public function setAutoBalanceVat(?bool $autoBalanceVat): self
    {
        $this->autoBalanceVat = $autoBalanceVat;

        return $this;
    }

    public function getRaiseWarning(): ?bool
    {
        return $this->raiseWarning;
    }

    public function setRaiseWarning(?bool $raiseWarning): self
    {
        $this->raiseWarning = $raiseWarning;

        return $this;
    }

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(?string $office): self
    {
        $this->office = $office;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(?string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getFreetext1(): ?string
    {
        return $this->freetext1;
    }

    public function setFreetext1(?string $freetext1): self
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    public function getFreetext2(): ?string
    {
        return $this->freetext2;
    }

    public function setFreetext2(?string $freetext2): self
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    public function getFreetext3(): ?string
    {
        return $this->freetext3;
    }

    public function setFreetext3(?string $freetext3): self
    {
        $this->freetext3 = $freetext3;

        return $this;
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function addLine(BaseTransactionLine $line): self
    {
        if (!is_a($line, $this->getLineClassName())) {
            throw Exception::invalidLineClassForTransaction($line, $this);
        }

        $this->lines[$line->getId()] = $line;

        return $this;
    }

    public function removeLine(string $lineId): void
    {
        if (!array_key_exists($lineId, $this->lines)) {
            throw Exception::transactionLineDoesNotExist($lineId);
        }

        unset($this->lines[$lineId]);
    }
}
