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

    abstract public function getType(): ?string;

    public function getVatTotal(): ?float
    {
        return $this->vatTotal;
    }

    public function setVatTotal(?float $vatTotal): BaseTransactionLine
    {
        if ($vatTotal !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('vatTotal', $this);
        }

        $this->vatTotal = $vatTotal;

        return $this;
    }

    public function getVatBaseTotal(): ?float
    {
        return $this->vatBaseTotal;
    }

    public function setVatBaseTotal(?float $vatBaseTotal): BaseTransactionLine
    {
        if ($vatBaseTotal !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('vatBaseTotal', $this);
        }

        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }
}
