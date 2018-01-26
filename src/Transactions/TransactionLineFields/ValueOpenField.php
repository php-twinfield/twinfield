<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;

trait ValueOpenField
{
    /**
     * @var Money|null Only if line type is total. Read-only attribute.
     *                 Sales transactions: The amount still owed in the currency of the sales transaction.
     *                 Purchase transaction: The amount still to be paid in the currency of the purchase transaction.
     */
    protected $valueOpen;

    abstract public function getLineType(): LineType;

    /**
     * @return Money|null
     */
    public function getValueOpen(): ?Money
    {
        return $this->valueOpen;
    }

    /**
     * @param Money|null $valueOpen
     * @return $this
     * @throws Exception
     */
    public function setValueOpen(?Money $valueOpen): self
    {
        if ($valueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('valueOpen', $this);
        }

        $this->valueOpen = $valueOpen;

        return $this;
    }
}
