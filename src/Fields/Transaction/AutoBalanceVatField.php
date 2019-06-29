<?php

namespace PhpTwinfield\Fields\Transaction;

trait AutoBalanceVatField
{
    /**
     * Auto balance VAT field
     * Used by: BaseTransaction
     *
     * @var bool
     */
    private $autoBalanceVat;

    /**
     * @return bool
     */
    public function getAutoBalanceVat(): ?bool
    {
        return $this->autoBalanceVat;
    }

    /**
     * @param bool $autoBalanceVat
     * @return $this
     */
    public function setAutoBalanceVat(?bool $autoBalanceVat): self
    {
        $this->autoBalanceVat = $autoBalanceVat;
        return $this;
    }
}
