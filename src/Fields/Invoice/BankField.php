<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\CashBankBook;

/**
 * The cash or bank book
 * Used by: Invoice
 *
 * @package PhpTwinfield\Traits
 */
trait BankField
{
    /**
     * @var CashBankBook|null
     */
    private $bank;

    public function getBank(): ?CashBankBook
    {
        return $this->bank;
    }

    /**
     * @return $this
     */
    public function setBank(?CashBankBook $bank): self
    {
        $this->bank = $bank;
        return $this;
    }
}
