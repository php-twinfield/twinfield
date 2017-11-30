<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\Exception;

trait PerformanceFields
{
    /**
     * @var string|null One of the BaseTransactionLine::PERFORMANCETYPE_* constants. Only if line type is detail or vat.
     *                  Mandatory in case of an ICT VAT code. The performance type.
     */
    protected $performanceType;

    /**
     * @var string|null Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes
     *                  are used. If not added to the request, by default the country code of the customer will be
     *                  taken.
     */
    protected $performanceCountry;

    /**
     * @var string|null Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the
     *                  request, by default the VAT number of the customer will be taken.
     */
    protected $performanceVatNumber;

    /**
     * @var string|null Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if
     *                  performancetype is services. The performance date in 'YYYYMMDD' format.
     */
    protected $performanceDate;

    /**
     * @return string|null
     */
    abstract public function getType(): ?string;

    /**
     * @return string|null
     */
    public function getPerformanceType(): ?string
    {
        return $this->performanceType;
    }

    /**
     * @param string|null $performanceType
     * @return $this
     * @throws Exception
     */
    public function setPerformanceType(?string $performanceType): self
    {
        if (
            $performanceType !== null &&
            !in_array($this->getType(), [BaseTransactionLine::TYPE_DETAIL, BaseTransactionLine::TYPE_VAT])
        ) {
            throw Exception::invalidFieldForLineType('performanceType', $this);
        }

        $this->performanceType = $performanceType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerformanceCountry(): ?string
    {
        return $this->performanceCountry;
    }

    /**
     * @param string|null $performanceCountry
     * @return $this
     * @throws Exception
     */
    public function setPerformanceCountry(?string $performanceCountry): self
    {
        if (
            $performanceCountry !== null &&
            !in_array($this->getType(), [BaseTransactionLine::TYPE_DETAIL, BaseTransactionLine::TYPE_VAT])
        ) {
            throw Exception::invalidFieldForLineType('performanceCountry', $this);
        }

        $this->performanceCountry = $performanceCountry;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerformanceVatNumber(): ?string
    {
        return $this->performanceVatNumber;
    }

    /**
     * @param string|null $performanceVatNumber
     * @return $this
     * @throws Exception
     */
    public function setPerformanceVatNumber(?string $performanceVatNumber): self
    {
        if (
            $performanceVatNumber !== null &&
            !in_array($this->getType(), [BaseTransactionLine::TYPE_DETAIL, BaseTransactionLine::TYPE_VAT])
        ) {
            throw Exception::invalidFieldForLineType('performanceVatNumber', $this);
        }

        $this->performanceVatNumber = $performanceVatNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerformanceDate(): ?string
    {
        return $this->performanceDate;
    }

    /**
     * @param string|null $performanceDate
     * @return $this
     * @throws Exception
     */
    public function setPerformanceDate(?string $performanceDate): self
    {
        if (
            $performanceDate !== null &&
            !in_array($this->getType(), [BaseTransactionLine::TYPE_DETAIL, BaseTransactionLine::TYPE_VAT])
        ) {
            throw Exception::invalidFieldForLineType('performanceDate', $this);
        }

        $this->performanceDate = $performanceDate;

        return $this;
    }
}
