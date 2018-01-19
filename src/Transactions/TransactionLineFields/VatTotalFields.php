<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;

/**
 * @todo $vatRepTotal Only if line type is total. The total VAT amount in reporting currency.
 */
trait VatTotalFields
{
    /**
     * @var Money|null Only if line type is total. The total VAT amount in the currency of the sales transaction.
     */
    protected $vatTotal;

    /**
     * @var Money|null Only if line type is total. The total VAT amount in base currency.
     */
    protected $vatBaseTotal;

    /**
     * @return LineType
     */
    abstract public function getType(): LineType;

    /**
     * @return Money|null
     */
    public function getVatTotal(): ?Money
    {
        return !empty($this->vatTotal) ? $this->vatTotal->absolute() : null;
    }

    /**
     * @param Money|null $vatTotal
     * @return $this
     * @throws Exception
     */
    public function setVatTotal(?Money $vatTotal): self
    {
        if ($vatTotal !== null && !$this->getType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatTotal', $this);
        }

        $this->vatTotal = $vatTotal;

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getVatBaseTotal(): ?Money
    {
        return !empty($this->vatBaseTotal) ? $this->vatBaseTotal->absolute() : null;
    }

    /**
     * @param Money|null $vatBaseTotal
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTotal(?Money $vatBaseTotal): self
    {
        if ($vatBaseTotal !== null && !$this->getType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('vatBaseTotal', $this);
        }

        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }
}
