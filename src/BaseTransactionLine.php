<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\MatchSet;
use PhpTwinfield\Transactions\TransactionLine;
use PhpTwinfield\Transactions\TransactionLineFields\CommentField;
use PhpTwinfield\Transactions\TransactionLineFields\FreeCharField;
use PhpTwinfield\Transactions\TransactionLineFields\ThreeDimFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatTurnoverFields;

/**
 * @todo $relation Only if line type is total (or detail for Journal and Cash transactions). Read-only attribute.
 * @todo $repValueOpen Meaning differs per transaction type. Read-only attribute.
 * @todo $vatBaseValue Only if line type is detail. VAT amount in base currency.
 * @todo $vatRepValue Only if line type is detail. VAT amount in reporting currency.
 * @todo $destOffice Office code. Used for inter company transactions.
 * @todo $comment Comment set on the transaction line.
 * @todo $matches Implement for BankTransactionLine
 *
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions
 */
abstract class BaseTransactionLine implements TransactionLine
{
    use ValueFields;
    use ThreeDimFields;
    use VatTurnoverFields;
    use CommentField;
    use FreeCharField;

    public const MATCHSTATUS_AVAILABLE    = 'available';
    public const MATCHSTATUS_MATCHED      = 'matched';
    public const MATCHSTATUS_PROPOSED     = 'proposed';
    public const MATCHSTATUS_NOTMATCHABLE = 'notmatchable';

    // Used in PerformanceFields trait.
    const PERFORMANCETYPE_SERVICES = 'services';
    const PERFORMANCETYPE_GOODS    = 'goods';

    /**
     * @var LineType
     */
    protected $lineType;

    /**
     * @var int|null The line ID.
     */
    protected $id;

    /**
     * @var Money|null Amount in the base currency.
     * @todo This field is currently read-only in this library.
     */
    protected $baseValue;

    /**
     * @var int|null
     */
    private $baseline;

    /**
     * @var float|null The exchange rate used for the calculation of the base amount.
     * @todo This field is currently read-only in this library.
     */
    protected $rate;

    /**
     * @var Money|null Amount in the reporting currency.
     * @todo This field is currently read-only in this library.
     */
    protected $repValue;

    /**
     * @var float|null The exchange rate used for the calculation of the reporting amount.
     * @todo This field is currently read-only in this library.
     */
    protected $repRate;

    /**
     * @var string|null Description of the transaction line. Max length is 40 characters.
     */
    protected $description;

    /**
     * @var string|null Payment status of the transaction. One of the self::MATCHSTATUS_* constants. Read-only
     *                  attribute. There are some restrictions for possible values depending on the sub class. See
     *                  explanation in the sub classes.
     */
    protected $matchStatus;

    /**
     * @var int|null The level of the matchable dimension. Read-only attribute. Only available for some line types,
     *               depending on the sub class. See the explanation in sub classes.
     */
    protected $matchLevel;

    /**
     * @var Money|null Meaning differs per transaction type. Read-only attribute. See explanation in the sub classes.
     */
    protected $baseValueOpen;

    /**
     * @var string|null Only if line type is detail or vat. VAT code.
     */
    protected $vatCode;

    /**
     * @var Money|null Only if line type is detail. VAT amount in the currency of the transaction.
     */
    protected $vatValue;

    /**
     * @var \DateTimeInterface|null Only if line type is detail. The line date. Only allowed if the line date in the
     *                              bank book is set to Allowed or Mandatory.
     */
    protected $currencyDate;

    /**
     * @var MatchSet[] Empty if not available, readonly.
     */
    protected $matches = [];

    public function getLineType(): LineType
    {
        return $this->lineType;
    }

    /**
     * @param LineType $lineType
     * @return $this
     */
    public function setLineType(LineType $lineType): BaseTransactionLine
    {
        $this->lineType = $lineType;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): BaseTransactionLine
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getBaseValue(): ?Money
    {
        return $this->baseValue;
    }

    /**
     * @param Money|null $baseValue
     * @return $this
     */
    public function setBaseValue(?Money $baseValue): BaseTransactionLine
    {
        $this->baseValue = $baseValue;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRate(): ?float
    {
        return $this->rate;
    }

    /**
     * @param float|null $rate
     * @return $this
     */
    public function setRate(?float $rate): BaseTransactionLine
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getRepValue(): ?Money
    {
        return $this->repValue;
    }

    /**
     * @param Money|null $repValue
     * @return $this
     */
    public function setRepValue(?Money $repValue): BaseTransactionLine
    {
        $this->repValue = $repValue;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRepRate(): ?float
    {
        return $this->repRate;
    }

    /**
     * @param float|null $repRate
     * @return $this
     */
    public function setRepRate(?float $repRate): BaseTransactionLine
    {
        $this->repRate = $repRate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): BaseTransactionLine
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMatchStatus(): ?string
    {
        return $this->matchStatus;
    }

    /**
     * @param string|null $matchStatus
     * @return $this
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        $this->matchStatus = $matchStatus;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMatchLevel(): ?int
    {
        return $this->matchLevel;
    }

    /**
     * @param int|null $matchLevel
     * @return $this
     */
    public function setMatchLevel(?int $matchLevel): BaseTransactionLine
    {
        $this->matchLevel = $matchLevel;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getBaseValueOpen(): ?Money
    {
        return $this->baseValueOpen;
    }

    /**
     * @param Money|null $baseValueOpen
     * @return $this
     */
    public function setBaseValueOpen(?Money $baseValueOpen): BaseTransactionLine
    {
        $this->baseValueOpen = $baseValueOpen;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVatCode(): ?string
    {
        return $this->vatCode;
    }

    /**
     * @param string|null $vatCode
     * @return $this
     * @throws Exception
     */
    public function setVatCode(?string $vatCode): BaseTransactionLine
    {
        if ($vatCode !== null && !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])) {
            throw Exception::invalidFieldForLineType('vatCode', $this);
        }

        $this->vatCode = $vatCode;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatValue(): ?Money
    {
        return !empty($this->vatValue) ? $this->vatValue->absolute() : null;
    }

    /**
     * @param Money|null $vatValue
     * @return $this
     * @throws Exception
     */
    public function setVatValue(?Money $vatValue): BaseTransactionLine
    {
        if ($vatValue !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('vatValue', $this);
        }

        $this->vatValue = $vatValue;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCurrencyDate(): ?\DateTimeInterface
    {
        return $this->currencyDate;
    }

    /**
     * @param \DateTimeInterface|null $currencyDate
     * @return $this
     * @throws Exception
     */
    public function setCurrencyDate(?\DateTimeInterface $currencyDate): self
    {
        if ($currencyDate !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('currencyDate', $this);
        }

        $this->currencyDate = $currencyDate;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBaseline(): ?int
    {
        return $this->baseline;
    }

    /**
     * @param int|null $baseline
     * @return $this
     */
    public function setBaseline(?int $baseline)
    {
        $this->baseline = $baseline;

        return $this;
    }

    /**
     * This will get you a unique reference to the object in Twinfield.
     *
     * With this reference, you can perform matching.
     *
     * @return MatchReferenceInterface
     */
    public function getReference(): MatchReferenceInterface
    {
        /** @var JournalTransaction|PurchaseTransaction|SalesTransaction $transaction */
        $transaction = $this->getTransaction();

        return new MatchReference(
            $transaction->getOffice(),
            $transaction->getCode(),
            $transaction->getNumber(),
            $this->getId()
        );
    }

    public function addMatch(MatchSet $match)
    {
        $this->matches[] = $match;
    }

    /**
     * @return MatchSet[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }
}
