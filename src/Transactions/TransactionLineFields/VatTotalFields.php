<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\Exception;

/**
 * @todo $vatRepTotal Only if line type is total. The total VAT amount in reporting currency.
 */
trait VatTotalFields
{
    /**
     * @var float|null Only if line type is total. The total VAT amount in the currency of the sales transaction.
     */
    protected $vatTotal;

    /**
     * @var float|null Only if line type is total. The total VAT amount in base currency.
     */
    protected $vatBaseTotal;

    /**
     * @return string|null
     */
    abstract public function getType(): ?string;

    /**
     * @return float|null
     */
    public function getVatTotal(): ?float
    {
        return $this->vatTotal;
    }

    /**
     * @param float|null $vatTotal
     * @return $this
     * @throws Exception
     */
    public function setVatTotal(?float $vatTotal): self
    {
        if ($vatTotal !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('vatTotal', $this);
        }

        $this->vatTotal = $vatTotal;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getVatBaseTotal(): ?float
    {
        return $this->vatBaseTotal;
    }

    /**
     * @param float|null $vatBaseTotal
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTotal(?float $vatBaseTotal): self
    {
        if ($vatBaseTotal !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('vatBaseTotal', $this);
        }

        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }
}
