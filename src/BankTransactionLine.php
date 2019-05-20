<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\Transaction\TransactionLine\CurrencyDateField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceCountryField;
use PhpTwinfield\Fields\Transaction\TransactionLine\PerformanceVatNumberField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatBaseTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatRepTotalField;
use PhpTwinfield\Fields\Transaction\TransactionLine\VatTotalField;
use Webmozart\Assert\Assert;

class BankTransactionLine extends BaseTransactionLine
{
    use CurrencyDateField;
    use PerformanceCountryField;
    use PerformanceDateField;
    use PerformanceTypeField;
    use PerformanceVatNumberField;
    use VatBaseTotalField;
    use VatRepTotalField;
    use VatTotalField;

    /*
     * @var BankTransaction
     */
    private $transaction;

    /*
     * @param BankTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, 'Attempting to set a transaction while the transaction is already set.');
        Assert::isInstanceOf($object, BankTransaction::class);
        $this->transaction = $object;
    }

    /*
     * References the transaction this line belongs too.
     *
     * @return BankTransaction
     */
    public function getTransaction(): BankTransaction
    {
        return $this->transaction;
    }

    /*
     * Only if line type is detail. The amount still owed in base currency. Read-only attribute.
     *
     * @param Money|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?Money $baseValueOpen): parent
    {
        if ($baseValueOpen !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }

    /*
     * Only if line type is detail. The line date. Only allowed if the line date in the bank book is set to Allowed or Mandatory.
     *
     * @param \DateTimeInterface|null $currencyDate
     * @return $this
     * @throws Exception
     */
    public function setCurrencyDate(?\DateTimeInterface $currencyDate): self
    {
        if ($currencyDate !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('currencyDate', $this);
        }

        return parent::setCurrencyDate($currencyDate);
    }

    /*
     * If line type = total empty.
     *
     * If line type = detail the customer or supplier or the cost center or empty.
     *
     * If line type = vat empty.
     *
     * @param $dim2
     * @return $this
     * @throws Exception
     */
    public function setDim2($dim2): parent
    {
        if ($dim2 !== null && ($this->getLineType()->equals(LineType::VAT()) || $this->getLineType()->equals(LineType::TOTAL()))) {
            throw Exception::invalidDimensionForLineType(2, $this);
        }

        return parent::setDim2($dim2);
    }

    /*
     * If line type = total empty.
     *
     * If line type = detail the project or asset or empty.
     *
     * If line type = vat empty.
     *
     * @param $dim3
     * @return $this
     * @throws Exception
     */
    public function setDim3($dim3): parent
    {
        if ($dim3 !== null && ($this->getLineType()->equals(LineType::VAT()) || $this->getLineType()->equals(LineType::TOTAL()))) {
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
        return true;
    }

    /*
     * Only if line type is detail. The level of the matchable dimension. Read-only attribute.
     *
     * @param int|null $matchLevel
     * @return $this
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): parent
    {
        if ($matchLevel !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /*
     * Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
     *
     * @param PerformanceType|null $performanceType
     * @return $this
     * @throws Exception
     */
    public function setPerformanceType(?PerformanceType $performanceType): self
    {
        if ($performanceType !== null && $this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('performanceType', $this);
        }

        return parent::setPerformanceType($performanceType);
    }

    /*
     * Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
     *
     * @param Country|null $performanceCountry
     * @return $this
     * @throws Exception
     */
    public function setPerformanceCountry(?Country $performanceCountry): self
    {
        if ($performanceCountry !== null && $this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('performanceCountry', $this);
        }

        return parent::setPerformanceCountry($performanceCountry);
    }

    /*
     * Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
     *
     * @param string|null $performanceVatNumber
     * @return $this
     * @throws Exception
     */
    public function setPerformanceVatNumber(?string $performanceVatNumber): self
    {
        if ($performanceVatNumber !== null && $this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('performanceVatNumber', $this);
        }

        return parent::setPerformanceVatNumber($performanceVatNumber);
    }

    /*
     * Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
     *
     * @param \DateTimeInterface|null $performanceDate
     * @return $this
     * @throws Exception
     */
    public function setPerformanceDate(?\DateTimeInterface $performanceDate): self
    {
        if ($performanceDate !== null && (!$this->getPerformanceType()->equals(PerformanceType::SERVICES()) || $this->getLineType()->equals(LineType::TOTAL()))) {
            throw Exception::invalidFieldForLineType('performanceDate', $this);
        }

        return parent::setPerformanceDate($performanceDate);
    }

    /*
     * Relation of the transaction. Only if line type is detail. Read-only attribute.
     *
     * @param string|null $relation
     * @return $this
     * @throws Exception
     */
    public function setRelation(?string $relation): parent
    {
        if ($relation !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidRelationForLineType($relation, $this);
        }

        return $this;
    }

    /*
     * Only if line type is detail. The amount still owed in reporting currency. Read-only attribute.
     *
     * @param Money|null $repValueOpen
     * @return $this
     * @throws Exception
     */
    public function setRepValueOpen(?Money $repValueOpen): parent
    {
        if ($repValueOpen !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('repValueOpen', $this);
        }

        return parent::setRepValueOpen($repValueOpen);
    }

    /*
     * Only if line type is total. The total VAT amount in the currency of the bank transaction
     *
     * @param Money|null $vatValueOpen
     * @return $this
     * @throws Exception
     */
    public function setVatValueOpen(?Money $vatValueOpen): parent
    {
        if ($vatValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatValueOpen', $this);
        }

        return parent::setVatValueOpen($vatValueOpen);
    }

    /*
     * Only if line type is total. The total VAT amount in base currency.
     *
     * @param Money|null $vatBaseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setVatBaseValueOpen(?Money $vatBaseValueOpen): parent
    {
        if ($vatBaseValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatBaseValueOpen', $this);
        }

        return parent::setVatBaseValueOpen($vatBaseValueOpen);
    }

    /*
     * Only if line type is total. The total VAT amount in reporting currency.
     *
     * @param Money|null $vatRepValueOpen
     * @return $this
     * @throws Exception
     */
    public function setVatRepValueOpen(?Money $vatRepValueOpen): parent
    {
        if ($vatRepValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatRepValueOpen', $this);
        }

        return parent::setVatRepValueOpen($vatRepValueOpen);
    }
}
