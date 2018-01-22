<?php

namespace PhpTwinfield\Transactions\BankTransactionLine;

use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Office;
use PhpTwinfield\ReferenceInterface;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
use PhpTwinfield\Transactions\TransactionLine;
use PhpTwinfield\Transactions\TransactionLineFields\CommentField;
use PhpTwinfield\Transactions\TransactionLineFields\ThreeDimFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

abstract class Base implements TransactionLine
{
    use ValueFields;
    use ThreeDimFields;
    use CommentField;

    /**
     * Note that the field is not in the documentation but it is in all the examples.
     *
     * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions
     */
    use InvoiceNumberField;

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
     * @var BankTransaction
     */
    private $transaction;

    /**
     * References the transaction this line belongs too.
     *
     * @return BankTransaction
     */
    public function getTransaction(): BankTransaction
    {
        return $this->transaction;
    }

    /**
     * @param BankTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::isInstanceOf($object, BankTransaction::class);
        $this->transaction = $object;
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
     * @return $this
     */
    final protected function setType(LineType $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return null|Office
     */
    public function getDestOffice(): ?Office
    {
        return $this->destOffice;
    }

    /**
     * Used for inter company transactions – here you define in which company the transaction line should be posted.
     *
     * @param Office $destOffice
     * @return $this
     */
    public function setDestOffice(Office $destOffice)
    {
        $this->destOffice = $destOffice;
        return $this;
    }

    /**
     * @return string
     */
    public function getFreeChar(): ?string
    {
        return $this->freeChar;
    }

    /**
     * @param string $freeChar
     * @return $this
     */
    public function setFreeChar(string $freeChar)
    {
        $this->freeChar = $freeChar;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getReference(): ReferenceInterface
    {
        return new class($this) implements ReferenceInterface
        {
            /**
             * @var string
             */
            private $number;

            /**
             * @var string
             */
            private $code;

            /**
             * @var Office
             */
            private $office;

            /**
             * @var int
             */
            private $lineId;


            public function __construct(Base $line)
            {
                $transaction = $line->getTransaction();

                $this->code   = $transaction->getCode();
                $this->number = $transaction->getNumber();
                $this->office = $transaction->getOffice();
                $this->lineId = $line->getId();
            }

            public function getNumber(): string
            {
                return $this->number;
            }

            public function getCode(): string
            {
                return $this->code;
            }

            public function getOffice(): Office
            {
                return $this->office;
            }

            public function getLineId(): int
            {
                return $this->lineId;
            }
        };
    }
}