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

    /**
     * @return $this
     */
    public function setPayCode(?PayCode $payCode): self
    {
        $this->payCode = $payCode;
        return $this;
    }
}
