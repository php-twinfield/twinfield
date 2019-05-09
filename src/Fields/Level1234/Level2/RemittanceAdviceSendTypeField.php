<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

use PhpTwinfield\Enums\RemittanceAdviceSendType;

trait RemittanceAdviceSendTypeField
{
    /**
     * Remittance advice send type field
     * Used by: Customer, Supplier
     *
     * @var RemittanceAdviceSendType|null
     */
    private $remittanceAdviceSendType;

    public function getRemittanceAdviceSendType(): ?RemittanceAdviceSendType
    {
        return $this->remittanceAdviceSendType;
    }

    /**
     * @param RemittanceAdviceSendType|null $remittanceAdviceSendType
     * @return $this
     */
    public function setRemittanceAdviceSendType(?RemittanceAdviceSendType $remittanceAdviceSendType): self
    {
        $this->remittanceAdviceSendType = $remittanceAdviceSendType;
        return $this;
    }

    /**
     * @param string|null $remittanceAdviceSendTypeString
     * @return $this
     * @throws Exception
     */
    public function setRemittanceAdviceSendTypeFromString(?string $remittanceAdviceSendTypeString)
    {
        return $this->setRemittanceAdviceSendType(new RemittanceAdviceSendType((string)$remittanceAdviceSendTypeString));
    }
}