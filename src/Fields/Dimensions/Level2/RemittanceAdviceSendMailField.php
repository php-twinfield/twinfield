<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

trait RemittanceAdviceSendMailField
{
    /**
     * Remittance advice send mail field
     * Used by: Customer, Supplier
     *
     * @var string|null
     */
    private $remittanceAdviceSendMail;

    /**
     * @return null|string
     */
    public function getRemittanceAdviceSendMail(): ?string
    {
        return $this->remittanceAdviceSendMail;
    }

    /**
     * @param null|string $remittanceAdviceSendMail
     * @return $this
     */
    public function setRemittanceAdviceSendMail(?string $remittanceAdviceSendMail): self
    {
        $this->remittanceAdviceSendMail = $remittanceAdviceSendMail;
        return $this;
    }
}