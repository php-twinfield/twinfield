<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait AutoBalanceVatField
{
    /**
     * Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
     *
     * @var bool
     */
    private $autoBalanceVat;

    /**
     * @return bool
     */
    public function isAutoBalanceVat(): ?bool
    {
        return $this->autoBalanceVat;
    }

    /**
     * @param bool $autoBalanceVat
     * @return $this
     */
    public function setAutoBalanceVat(bool $autoBalanceVat): self
    {
        $this->autoBalanceVat = $autoBalanceVat;

        return $this;
    }
}