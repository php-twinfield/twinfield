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

    public function getAutoBalanceVatToString(): ?string
    {
        return ($this->getAutoBalanceVat()) ? 'true' : 'false';
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

    /**
     * @param string|null $autoBalanceVatString
     * @return $this
     * @throws Exception
     */
    public function setAutoBalanceVatFromString(?string $autoBalanceVatString)
    {
        return $this->setAutoBalanceVat(filter_var($autoBalanceVatString, FILTER_VALIDATE_BOOLEAN));
    }
}