<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\MatchStatus;
use PhpTwinfield\Fields\CommentField;
use PhpTwinfield\Fields\DescriptionField;
use PhpTwinfield\Fields\Dim2Field;
use PhpTwinfield\Fields\Dim3Field;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\LineTypeField;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaseValueField;
use PhpTwinfield\Fields\Transaction\TransactionLine\BaseValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\DestOfficeField;
use PhpTwinfield\Fields\Transaction\TransactionLine\Dim1Field;
use PhpTwinfield\Fields\Transaction\TransactionLine\FreeCharField;
use PhpTwinfield\Fields\Transaction\TransactionLine\MatchLevelField;
use PhpTwinfield\Fields\Transaction\TransactionLine\MatchStatusField;
use PhpTwinfield\Fields\Transaction\TransactionLine\RateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\RelationField;
use PhpTwinfield\Fields\Transaction\TransactionLine\RepRateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\RepValueField;
use PhpTwinfield\Fields\Transaction\TransactionLine\RepValueOpenField;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueFields;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTurnoverField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseValueField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepTurnoverField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepValueField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTurnoverField;
use PhpTwinfield\Fields\VatCodeField;
use PhpTwinfield\Fields\VatValueField;

abstract class BaseTransactionLine
{
    use BaseValueField;
    use BaseValueOpenField;
    use CommentField;
    use DescriptionField;
    use DestOfficeField;
    use Dim1Field;
    use Dim2Field;
    use Dim3Field;
    use FreeCharField;
    use IDField;
    use LineTypeField;
    use MatchLevelField;
    use MatchStatusField;
    use RateField;
    use RelationField;
    use RepRateField;
    use RepValueField;
    use RepValueOpenField;
    use ValueFields;
    use VatBaseTurnoverField;
    use VatBaseValueField;
    use VatCodeField;
    use VatRepTurnoverField;
    use VatRepValueField;
    use VatTurnoverField;
    use VatValueField;

    /*
     * Set the transaction on the line. This is needed later on.
     *
     * @param $object
     * @throws \InvalidArgumentException If a transaction is invalid or if a transaction is already set.
     * @internal
     */
    public function setTransaction($object): void;

    /*
     * Gets the transaction from the line.
     *
     * Note that you should add the return type when implementing.
     *
     * @see MatchReferenceInterface
     * @internal
     */
    public function getTransaction();

    /*
     * This will get you a unique reference to the object in Twinfield.
     *
     * With this reference, you can perform matching.
     *
     * @return MatchReferenceInterface
     */
    public function getReference(): MatchReferenceInterface
    {
        /* @var BankTransaction|CashTransaction|JournalTransaction|PurchaseTransaction|SalesTransaction $transaction */
        $transaction = $this->getTransaction();

        return new MatchReference(
            $transaction->getOffice(),
            $transaction->getCode(),
            $transaction->getNumber(),
            $this->getId()
        );
    }

    /*
     * Payment status of the transaction. If line type total or vat always notmatchable. Read-only attribute.
     *
     * @param MatchStatus|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?MatchStatus $matchStatus): self
    {
        if ($matchStatus !== null && in_array($this->getLineType(), [LineType::TOTAL(), LineType::VAT()]) && $matchStatus != MatchStatus::NOTMATCHABLE()) {
            throw Exception::invalidMatchStatusForLineType($matchStatus, $this);
        }

        return $this;
    }

    /*
     * Only if line type is vat. Amount on which VAT was calculated in base currency.
     *
     * @param Money|null $vatBaseTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTurnover(?Money $vatBaseTurnover): self
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatbaseturnover', $this);
        }
        
        return $this;
    }

    /*
     * @param Money|null $vatBaseValue
     * @return $this
     * @throws Exception
     */
    public function setVatBaseValue(?Money $vatBaseValue): self
    {
        if ($vatBaseValue !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('vatBaseValue', $this);
        }

        $this->vatBaseValue = $vatBaseValue;

        return $this;
    }

    /*
     * @param VatCode|null $vatCode
     * @return $this
     * @throws Exception
     */
     public function setVatCode(?VatCode $vatCode): self
    {
        if ($vatCode !== null && !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])) {
            throw Exception::invalidFieldForLineType('vatCode', $this);
        }

        $this->vatCode = $vatCode;
        return $this;
    }

    /*
     * Only if line type is vat. Amount on which VAT was calculated in reporting currency.
     *
     * @param Money|null $vatRepTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatRepTurnover(?Money $vatRepTurnover): self
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatrepturnover', $this);
        }
        
        return $this;
    }

    /*
     * @param Money|null $vatRepValue
     * @return $this
     * @throws Exception
     */
    public function setVatRepValue(?Money $vatRepValue): self
    {
        if ($vatRepValue !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('vatRepValue', $this);
        }

        $this->vatRepValue = $vatRepValue;
        return $this;
    }

    /*
     * Only if line type is vat. Amount on which VAT was calculated in the currency of the sales transaction.
     *
     * @param Money|null $vatTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatTurnover(?Money $vatTurnover): self
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatturnover', $this);
        }
        
        return $this;
    }

    /*
     * @param Money|null $vatValue
     * @return $this
     * @throws Exception
     */
    public function setVatValue(?Money $vatValue): self
    {
        if ($vatValue !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('vatValue', $this);
        }

        $this->vatValue = $vatValue;
        return $this;
    }
}
