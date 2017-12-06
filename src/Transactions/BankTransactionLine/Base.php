<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;

abstract class Base
{
    use ValueFields;

    /**
     * @var string
     */
    protected $dim1;
    /**
     * @var string
     */
    protected $dim2;
    /**
     * @var string
     */
    protected $dim3;
    /**
     * Line ID.
     *
     * @var int
     */
    private $id;
    /**
     * @var LineType
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Office
     */
    private $destOffice;

    /**
     * Free character field. (1 char)
     *
     * @var string
     */
    private $freeChar;

    /**
     * Comment set on the transaction line.
     *
     * @var string
     */
    private $comment;

    /**
     * @return string
     */
    final public function getDim1(): ?string
    {
        return $this->dim1;
    }

    /**
     * @return string
     */
    final public function getDim2(): ?string
    {
        return $this->dim2;
    }

    /**
     * @return string
     */
    final public function getDim3(): ?string
    {
        return $this->dim3;
    }

    /**
     * @return LineType
     */
    final public function getType(): LineType
    {
        return $this->type;
    }

    /**
     * @param LineType $type
     */
    final protected function setType(LineType $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Office
     */
    public function getDestOffice(): Office
    {
        return $this->destOffice;
    }

    /**
     * @param Office $destOffice
     */
    public function setDestOffice(Office $destOffice): void
    {
        $this->destOffice = $destOffice;
    }

    /**
     * @return string
     */
    public function getFreeChar(): string
    {
        return $this->freeChar;
    }

    /**
     * @param string $freeChar
     */
    public function setFreeChar(string $freeChar): void
    {
        $this->freeChar = $freeChar;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}