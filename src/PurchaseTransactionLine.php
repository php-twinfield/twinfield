<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaselineField;
use PhpTwinfield\Fields\Transaction\TransactionLine\MatchDateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
use Webmozart\Assert\Assert;

class PurchaseTransactionLine extends BaseTransactionLine
{
    use BaselineField;
    use MatchDateField;
    use ValueOpenField;
    use VatBaseTotalField;
    use VatRepTotalField;
    use VatTotalField;

    /*
     * @var PurchaseTransaction
     */
    private $transaction;

    /*
     * @param PurchaseTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, "Attempting to set a transaction while the transaction is already set.");
        Assert::isInstanceOf($object, PurchaseTransaction::class);
        $this->transaction = $object;
    }

    /*
     * References the transaction this line belongs too.
     *
     * @return PurchaseTransaction
     */
    public function getTransaction(): PurchaseTransaction
    {
        return $this->transaction;
    }

    /*
     * Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
     *
     * @param int|null $baseline
     * @return $this
     * @throws Exception
     */
    public function setBaseline(?int $baseline): self
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType("baseline", $this);
        }
        return $this;
    }

    /*
     * Only if line type is total. The amount still to be paid in base currency. Read-only attribute.
     *
     * @param Money|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?Money $baseValueOpen): parent
    {
        if ($baseValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }

    /*
     * If line type = total empty.
     *
     * If line type = detail the project or asset or empty.
     *
     * If line type = vat the project or asset or empty.
     *
     * @param $dim3
     * @return $this
     * @throws Exception
     */
    public function setDim3($dim3): parent
    {
        if ($dim3 !== null && $this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidDimensionForLineType(3, $this);
        }

        return parent::setDim3($dim3);
    }

    /*
     * Returns true if a positive amount in the TOTAL line means the amount is 'debit'. Examples of incoming transaction
     * types are Sales Transactions, Electronic Bank Statements and Bank Transactions.
     *
     * Returns false if a positive amount in the TOTAL line means the amount is 'credit'. An example of an outgoing
     * transaction type is a Purchase Transaction.
     *
     * @return bool
     */
    protected function isIncomingTransactionType(): bool
    {
        return false;
    }

    /*
     * Only if line type is total. The date on which the purchase invoice is matched. Read-only attribute.
     *
     * @param \DateTimeInterface|null $matchDate
     * @return $this
     * @throws Exception
     */
    public function setMatchDate(?\DateTimeInterface $matchDate): self
    {
        if ($matchDate !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('matchDate', $this);
        }

        return $this;
    }

    /*
     * Only if line type is total. The level of the matchable dimension. Read-only attribute.
     *
     * @param int|null $matchLevel
     * @return $this
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): parent
    {
        if ($matchLevel !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }
    
    /*
     * Payment status of the transaction. If line type detail or vat always notmatchable. Read-only attribute.
     *
     * @param MatchStatus|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?MatchStatus $matchStatus): parent
    {
        if ($matchStatus !== null && in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()]) && $matchStatus != MatchStatus::NOTMATCHABLE()) {
            throw Exception::invalidMatchStatusForLineType($matchStatus, $this);
        }

        return parent::setMatchStatus($matchStatus);
    }

    /*
     * Relation of the transaction. Only if line type is total. Read-only attribute.
     *
     * @param int|null $relation
     * @return $this
     * @throws Exception
     */
    public function setRelation(?int $relation): parent
    {
        if ($relation !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('relation', $this);
        }

        return parent::setRelation($relation);
    }

    /*
     * Only if line type is total. The amount still owed in reporting currency. Read-only attribute.
     *
     * @param Money|null $repValueOpen
     * @return $this
     * @throws Exception
     */
    public function setRepValueOpen(?Money $repValueOpen): parent
    {
        if ($repValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('repValueOpen', $this);
        }

        return parent::setRepValueOpen($repValueOpen);
    }

    /*
     * Only if line type is total. The amount still to be paid in the currency of the purchase transaction. Read-only attribute.
     *
     * @param Money|null $valueOpen
     * @return $this
     * @throws Exception
     */
    public function setValueOpen(?Money $valueOpen): self
    {
        if ($valueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('valueOpen', $this);
        }

        return $this;
    }

    /*
     * Only if line type is total. The total VAT amount in the currency of the purchase transaction
     *
     * @param Money|null $vatTotal
     * @return $this
     * @throws Exception
     */
    public function setVatTotal(?Money $vatTotal): self
    {
        if ($vatTotal !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatTotal', $this);
        }

        return $this;
    }

    /*
     * Only if line type is total. The total VAT amount in base currency.
     *
     * @param Money|null $vatBaseTotal
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTotal(?Money $vatBaseTotal): self
    {
        if ($vatBaseTotal !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatBaseTotal', $this);
        }

        return $this;
    }

    /*
     * Only if line type is total. The total VAT amount in reporting currency.
     *
     * @param Money|null $vatRepTotal
     * @return $this
     * @throws Exception
     */
    public function setVatRepTotal(?Money $vatRepTotal): self
    {
        if ($vatRepTotal !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatRepTotal', $this);
        }

        return $this;
    }
}
