<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\BaseTransactionLine;
use PhpTwinfield\Exception;

trait ValueOpenField
{
    /**
     * @var float|null Only if line type is total. Read-only attribute.
     *                 Sales transactions: The amount still owed in the currency of the sales transaction.
     *                 Purchase transaction: The amount still to be paid in the currency of the purchase transaction.
     */
    protected $valueOpen;

    /**
     * @return string|null
     */
    abstract public function getType(): ?string;

    /**
     * @return float|null
     */
    public function getValueOpen(): ?float
    {
        return $this->valueOpen;
    }

    /**
     * @param float|null $valueOpen
     * @return $this
     * @throws Exception
     */
    public function setValueOpen(?float $valueOpen): self
    {
        if ($valueOpen !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('valueOpen', $this);
        }

        $this->valueOpen = $valueOpen;

        return $this;
    }
}
