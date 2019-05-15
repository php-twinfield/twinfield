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

    public function getExchangeQuotaLockedToString(): ?string
    {
        return ($this->getExchangeQuotaLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $exchangeQuotaLockedString
     * @return $this
     * @throws Exception
     */
    public function setExchangeQuotaLockedFromString(?string $exchangeQuotaLockedString)
    {
        return $this->setExchangeQuotaLocked(filter_var($exchangeQuotaLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}