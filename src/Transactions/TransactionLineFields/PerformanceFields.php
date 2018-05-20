<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Exception;

trait PerformanceFields
{
    /**
     * @var PerformanceType|null Only if line type is detail or vat. Mandatory in case of an ICT VAT code.
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
     * @var \DateTimeInterface|null Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if
     *                              performancetype is services.
     */
    protected $performanceDate;

    abstract public function getLineType(): LineType;

    /**
     * @return PerformanceType|null
     */
    public function getPerformanceType(): ?PerformanceType
    {
        return $this->performanceType;
    }

    /**
     * @param PerformanceType|null $performanceType
     * @return $this
     * @throws Exception
     */
    public function setPerformanceType(?PerformanceType $performanceType): self
    {
        if (
            $performanceType !== null &&
            !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])
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
            !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])
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
            !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])
        ) {
            throw Exception::invalidFieldForLineType('performanceVatNumber', $this);
        }

        $this->performanceVatNumber = $performanceVatNumber;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPerformanceDate(): ?\DateTimeInterface
    {
        return $this->performanceDate;
    }

    /**
     * @param \DateTimeInterface|null $performanceDate
     * @return $this
     * @throws Exception
     */
    public function setPerformanceDate(?\DateTimeInterface $performanceDate): self
    {
        if (
            $performanceDate !== null &&
            !in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()])
        ) {
            throw Exception::invalidFieldForLineType('performanceDate', $this);
        }

        $this->performanceDate = $performanceDate;

        return $this;
    }
}
