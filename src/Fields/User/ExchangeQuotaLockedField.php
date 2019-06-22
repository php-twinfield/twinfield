<?php

namespace PhpTwinfield\Fields\User;

trait ExchangeQuotaLockedField
{
    /**
     * Exchange quota locked field
     * Used by: User
     *
     * @var bool
     */
    private $exchangeQuotaLocked;

    /**
     * @return bool
     */
    public function getExchangeQuotaLocked(): ?bool
    {
        return $this->exchangeQuotaLocked;
    }

    /**
     * @param bool $exchangeQuotaLocked
     * @return $this
     */
    public function setExchangeQuotaLocked(?bool $exchangeQuotaLocked): self
    {
        $this->exchangeQuotaLocked = $exchangeQuotaLocked;
        return $this;
    }
}