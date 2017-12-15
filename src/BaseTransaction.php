<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionLineFields\DateField;
use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;
use Webmozart\Assert\Assert;

/**
 * @todo $modificationDate The date/time on which the sales transaction was modified the last time. Read-only attribute.
 * @todo $user The user who created the sales transaction. Read-only attribute.
 * @todo $inputDate The date/time on which the transaction was created. Read-only attribute.
 */
abstract class BaseTransaction extends BaseObject
{
    use DestinyField;
    use AutoBalanceVatField;
    use OfficeField;
    use PeriodField;
    use FreeTextFields;
    use DateField;

    /**
     * @var bool|null Should warnings be given or not?
     */
    public $raiseWarning;

    /**
     * @var string|null The transaction type code.
     */
    public $code;

    /**
     * @var int|null The transaction number.
     */
    public $number;

    /**
     * @var string|null The currency code.
     */
    public $currency;

    /**
     * @var string|null The sales transaction origin. Read-only attribute.
     */
    public $origin;

    /**
     * @var BaseTransactionLine[]
     */
    public $lines = [];

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
     * @return BaseTransactionLine[]
     */
    public function getLines(): array
    {
        /*
         * When creating the XML that is send to Twinfield, the total lines should always be put first.
         * Twinfield returns an error when the total line is not the first line.
         */
        uasort($this->lines, function(BaseTransactionLine $a, BaseTransactionLine $b): int {
            if ($a->getType() == LineType::TOTAL()) {
                return -1;
            }

            if ($b->getType() == LineType::TOTAL()) {
                return 1;
            }

            return $a->getId() <=> $b->getId();
        });

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

        Assert::notNull($line->getId());

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
