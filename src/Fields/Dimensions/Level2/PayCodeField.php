<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

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
     * @param string|null $payCodeString
     * @return $this
     * @throws Exception
     */
    public function setPayCodeFromString(?string $payCodeString)
    {
        $payCode = new PayCode();
        $payCode->setCode($payCodeString);
        return $this->setPayCode($payCode);
    }
}
