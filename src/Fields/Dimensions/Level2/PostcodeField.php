<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait PostcodeField
{
    /**
     * Postcode field
     * Used by: CustomerAddress, CustomerBank, SupplierAddress, SupplierBank
     *
     * @var string|null
     */
    private $postcode;

    /**
     * @return null|string
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @param null|string $postcode
     * @return $this
     */
    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;
        return $this;
    }
}
