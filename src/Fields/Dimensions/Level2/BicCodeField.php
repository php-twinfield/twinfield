<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait BicCodeField
{
    /**
     * BIC code field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $bicCode;

    /**
     * @return null|string
     */
    public function getBicCode(): ?string
    {
        return $this->bicCode;
    }

    /**
     * @param null|string $bicCode
     * @return $this
     */
    public function setBicCode(?string $bicCode): self
    {
        $this->bicCode = $bicCode;
        return $this;
    }
}