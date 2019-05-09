<?php

namespace PhpTwinfield\Fields\Office;

trait VatPeriodField
{
    /**
     * Vat period field
     * Used by: Office
     *
     * @var string|null
     */
    private $vatPeriod;

    /**
     * @return null|string
     */
    public function getVatPeriod(): ?string
    {
        return $this->vatPeriod;
    }

    /**
     * @param null|string $vatPeriod
     * @return $this
     */
    public function setVatPeriod(?string $vatPeriod): self
    {
        $this->vatPeriod = $vatPeriod;
        return $this;
    }
}