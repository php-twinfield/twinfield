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

    public function getBankToCode(): ?string
    {
        if ($this->getBank() != null) {
            return $this->bank->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setBank(?CashBankBook $bank): self
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * @param string|null $bankCode
     * @return $this
     * @throws Exception
     */
    public function setBankFromCode(?string $bankCode)
    {
        $bank = new CashBankBook();
        $bank->setCode($bankCode);
        return $this->setBank($bank);
    }
}
