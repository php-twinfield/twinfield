<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait FreeText1Field
{
    /**
     * Free text 1 field
     * Used by: CustomerCreditManagement
     *
     * @var bool
     */
    private $freeText1;

    /**
     * @return bool
     */
    public function getFreeText1(): ?bool
    {
        return $this->freeText1;
    }

    /**
     * @param bool $freeText1
     * @return $this
     */
    public function setFreeText1(?bool $freeText1): self
    {
        $this->freeText1 = $freeText1;
        return $this;
    }
}
