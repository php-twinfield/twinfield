<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;

/**
 * @todo $modificationDate The date/time on which the sales transaction was modified the last time. Read-only attribute.
 * @todo $user The user who created the sales transaction. Read-only attribute.
 * @todo $inputDate The date/time on which the transaction was created. Read-only attribute.
 */
abstract class BaseTransaction extends BaseObject
{
    use DestinyField;

    use AutoBalanceVatField;

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

    /**
     * @return bool|null
     */
    public function getRaiseWarning(): ?bool
    {
        return $this->raiseWarning;
    }

    /**
     * @param bool|null $raiseWarning
     * @return $this
     */
    public function setRaiseWarning(?bool $raiseWarning): BaseTransaction
    {
        $this->raiseWarning = $raiseWarning;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOffice(): ?string
    {
        return $this->office;
    }

    /**
     * @param string|null $office
     * @return $this
     */
    public function setOffice(?string $office): BaseTransaction
    {
        $this->office = $office;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return $this
     */
    public function setCode(?string $code): BaseTransaction
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int|null $number
     * @return $this
     */
    public function setNumber(?int $number): BaseTransaction
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPeriod(): ?string
    {
        return $this->period;
    }

    /**
     * @param string|null $period
     * @return $this
     */
    public function setPeriod(?string $period): BaseTransaction
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return $this
     */
    public function setCurrency(?string $currency): BaseTransaction
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string|null $date
     * @return $this
     */
    public function setDate(?string $date): BaseTransaction
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @param string|null $origin
     * @return $this
     */
    public function setOrigin(?string $origin): BaseTransaction
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFreetext1(): ?string
    {
        return $this->freetext1;
    }

    /**
     * @param string|null $freetext1
     * @return $this
     */
    public function setFreetext1(?string $freetext1): BaseTransaction
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFreetext2(): ?string
    {
        return $this->freetext2;
    }

    /**
     * @param string|null $freetext2
     * @return $this
     */
    public function setFreetext2(?string $freetext2): BaseTransaction
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFreetext3(): ?string
    {
        return $this->freetext3;
    }

    /**
     * @param string|null $freetext3
     * @return $this
     */
    public function setFreetext3(?string $freetext3): BaseTransaction
    {
        $this->freetext3 = $freetext3;

        return $this;
    }

    /**
     * @return array
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @param BaseTransactionLine $line
     * @return $this
     * @throws Exception
     */
    public function addLine(BaseTransactionLine $line): BaseTransaction
    {
        if (!is_a($line, $this->getLineClassName())) {
            throw Exception::invalidLineClassForTransaction($line, $this);
        }

        $this->lines[$line->getId()] = $line;

        return $this;
    }

    /**
     * @param string $lineId
     * @throws Exception
     */
    public function removeLine(string $lineId): void
    {
        if (!array_key_exists($lineId, $this->lines)) {
            throw Exception::transactionLineDoesNotExist($lineId);
        }

        unset($this->lines[$lineId]);
    }
}
