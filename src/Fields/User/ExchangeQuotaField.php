<?php

namespace PhpTwinfield\Fields\User;

trait ExchangeQuotaField
{
    /**
     * Exchange quota field
     * Used by: User
     *
     * @var int|null
     */
    private $exchangeQuota;

    /**
     * @return null|int
     */
    public function getExchangeQuota(): ?int
    {
        return $this->exchangeQuota;
    }

    /**
     * @param null|int $exchangeQuota
     * @return $this
     */
    public function setExchangeQuota(?int $exchangeQuota): self
    {
        $this->exchangeQuota = $exchangeQuota;
        return $this;
    }
}