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

    abstract public function getType(): ?string;

    public function getValueOpen(): ?float
    {
        return $this->valueOpen;
    }

    public function setValueOpen(?float $valueOpen): BaseTransactionLine
    {
        if ($valueOpen !== null && $this->getType() != BaseTransactionLine::TYPE_TOTAL) {
            throw Exception::invalidFieldForLineType('valueOpen', $this);
        }

        $this->valueOpen = $valueOpen;

        return $this;
    }
}
