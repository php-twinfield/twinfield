<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait AscriptionField
{
    /**
     * Ascription field
     * Used by: CustomerBank, SupplierBank
     *
     * @var string|null
     */
    private $ascription;

    /**
     * @return null|string
     */
    public function getAscription(): ?string
    {
        return $this->ascription;
    }

    /**
     * @param null|string $ascription
     * @return $this
     */
    public function setAscription(?string $ascription): self
    {
        $this->ascription = $ascription;
        return $this;
    }
}