<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

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
}
