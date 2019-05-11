<?php

namespace PhpTwinfield\Fields\Level1234\Level2\Customer;

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

    public function getFreeText1ToString(): ?string
    {
        return ($this->getFreeText1()) ? 'true' : 'false';
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

    /**
     * @param string|null $freeText1String
     * @return $this
     * @throws Exception
     */
    public function setFreeText1FromString(?string $freeText1String)
    {
        return $this->setFreeText1(filter_var($freeText1String, FILTER_VALIDATE_BOOLEAN));
    }
}