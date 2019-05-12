<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait EBillMailField
{
    /**
     * E-bill mail field
     * Used by: CustomerFinancials
     *
     * @var string|null
     */
    private $eBillMail;

    /**
     * @return null|string
     */
    public function getEBillMail(): ?string
    {
        return $this->eBillMail;
    }

    /**
     * @param null|string $eBillMail
     * @return $this
     */
    public function setEBillMail(?string $eBillMail): self
    {
        $this->eBillMail = $eBillMail;
        return $this;
    }
}