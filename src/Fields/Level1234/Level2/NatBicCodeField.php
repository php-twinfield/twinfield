<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

trait NatBicCodeField
{
    /**
     * National BIC code field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $natBicCode;

    /**
     * @return null|string
     */
    public function getNatBicCode(): ?string
    {
        return $this->natBicCode;
    }

    /**
     * @param null|string $natBicCode
     * @return $this
     */
    public function setNatBicCode(?string $natBicCode): self
    {
        $this->natBicCode = $natBicCode;
        return $this;
    }
}