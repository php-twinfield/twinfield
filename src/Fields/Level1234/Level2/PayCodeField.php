<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

use PhpTwinfield\PayCode;

/**
 * The pay code
 * Used by: CustomerFinancials, SupplierFinancials
 *
 * @package PhpTwinfield\Traits
 */
trait PayCodeField
{
    /**
     * @var PayCode|null
     */
    private $payCode;

    public function getPayCode(): ?PayCode
    {
        return $this->payCode;
    }

    public function getPayCodeToString(): ?string
    {
        if ($this->getPayCode() != null) {
            return $this->payCode->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setPayCode(?PayCode $payCode): self
    {
        $this->payCode = $payCode;
        return $this;
    }

    /**
     * @param string|null $payCodeCode
     * @return $this
     * @throws Exception
     */
    public function setPayCodeFromString(?string $payCodeCode)
    {
        $payCode = new PayCode();
        $payCode->setCode($payCodeCode);
        return $this->setPayCode($payCode);
    }
}
