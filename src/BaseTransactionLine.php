<?php

namespace PhpTwinfield;

/**
 * @todo $dim3 Meaning differs per transaction type.
 * @todo $relation Only if line type is total (or detail for Journal transactions). Read-only attribute.
 * @todo $repValueOpen Meaning differs per transaction type. Read-only attribute.
 * @todo $vatBaseValue Only if line type is detail. VAT amount in base currency.
 * @todo $vatRepValue Only if line type is detail. VAT amount in reporting currency.
 * @todo $vatTurnover Only if line type is vat. Amount on which VAT was calculated in the currency of the transaction.
 * @todo $vatBaseTurnover Only if line type is vat. Amount on which VAT was calculated in base currency.
 * @todo $vatRepTurnover Only if line type is vat. Amount on which VAT was calculated in reporting currency.
 * @todo $baseline Only if line type is vat. Value of the baseline tag is a reference to the line ID of the VAT rate.
 * @todo $destOffice Office code. Used for inter company transactions.
 * @todo $freeChar Free character field. Meaning differs per transaction type.
 * @todo $comment Comment set on the transaction line.
 * @todo $matches Contains matching information. Read-only attribute.
 */
abstract class BaseTransactionLine
{
    public const TYPE_DETAIL = 'detail';
    public const TYPE_VAT    = 'vat';
    public const TYPE_TOTAL  = 'total';

    public const DEBIT  = 'debit';
    public const CREDIT = 'credit';

    public const MATCHSTATUS_AVAILABLE    = 'available';
    public const MATCHSTATUS_MATCHED      = 'matched';
    public const MATCHSTATUS_PROPOSED     = 'proposed';
    public const MATCHSTATUS_NOTMATCHABLE = 'notmatchable';

    // Used in PerformanceFields trait.
    const PERFORMANCETYPE_SERVICES = 'services';
    const PERFORMANCETYPE_GOODS    = 'goods';

    /**
     * @var string|null Either self::TYPE_TOTAL, self::TYPE_DETAIL or self::TYPE_VAT.
     */
    protected $type;

    /**
     * @var string|null The line ID.
     */
    protected $id;

    /**
     * @var string|null Meaning changes per transaction type, see explanation in sub classes.
     */
    protected $dim1;

    /**
     * @var string|null Meaning changes per transaction type, see explanation in sub classes.
     */
    protected $dim2;

    /**
     * @var string|null Either self::DEBIT or self::CREDIT. Meaning changes per transaction type, see explanation in sub
     *                  classes.
     */
    protected $debitCredit;

    /**
     * @var float|null Meaning changes per transaction type, see explanation in sub classes.
     */
    protected $value;

    /**
     * @var float|null Amount in the base currency.
     * @todo This field is currently read-only in this library.
     */
    protected $baseValue;

    /**
     * @var float|null The exchange rate used for the calculation of the base amount.
     * @todo This field is currently read-only in this library.
     */
    protected $rate;

    /**
     * @var float|null Amount in the reporting currency.
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
     * @var float|null Meaning differs per transaction type. Read-only attribute. See explanatio in the sub classes.
     */
    protected $baseValueOpen;

    /**
     * @var string|null Only if line type is detail or vat. VAT code.
     */
    protected $vatCode;

    /**
     * @var float|null Only if line type is detail. VAT amount in the currency of the transaction.
     */
    protected $vatValue;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): BaseTransactionLine
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return $this
     */
    public function setId(?string $id): BaseTransactionLine
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDim1(): ?string
    {
        return $this->dim1;
    }

    /**
     * @param string|null $dim1
     * @return $this
     */
    public function setDim1(?string $dim1): BaseTransactionLine
    {
        $this->dim1 = $dim1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDim2(): ?string
    {
        return $this->dim2;
    }

    /**
     * @param string|null $dim2
     * @return $this
     */
    public function setDim2(?string $dim2): BaseTransactionLine
    {
        $this->dim2 = $dim2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDebitCredit(): ?string
    {
        return $this->debitCredit;
    }

    /**
     * @param string|null $debitCredit
     * @return $this
     */
    public function setDebitCredit(?string $debitCredit): BaseTransactionLine
    {
        $this->debitCredit = $debitCredit;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     * @return $this
     */
    public function setValue(?float $value): BaseTransactionLine
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getBaseValue(): ?float
    {
        return $this->baseValue;
    }

    /**
     * @param float|null $baseValue
     * @return $this
     */
    public function setBaseValue(?float $baseValue): BaseTransactionLine
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
     * @return float|null
     */
    public function getRepValue(): ?float
    {
        return $this->repValue;
    }

    /**
     * @param float|null $repValue
     * @return $this
     */
    public function setRepValue(?float $repValue): BaseTransactionLine
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
     * @return float|null
     */
    public function getBaseValueOpen(): ?float
    {
        return $this->baseValueOpen;
    }

    /**
     * @param float|null $baseValueOpen
     * @return $this
     */
    public function setBaseValueOpen(?float $baseValueOpen): BaseTransactionLine
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
        if ($vatCode !== null && !in_array($this->getType(), [self::TYPE_DETAIL, self::TYPE_VAT])) {
            throw Exception::invalidFieldForLineType('vatCode', $this);
        }

        $this->vatCode = $vatCode;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getVatValue(): ?float
    {
        return $this->vatValue;
    }

    /**
     * @param float|null $vatValue
     * @return $this
     * @throws Exception
     */
    public function setVatValue(?float $vatValue): BaseTransactionLine
    {
        if ($vatValue !== null && $this->getType() != self::TYPE_DETAIL) {
            throw Exception::invalidFieldForLineType('vatValue', $this);
        }

        $this->vatValue = $vatValue;

        return $this;
    }
}
