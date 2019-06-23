<?php

namespace PhpTwinfield\Fields\Dimensions\Level2;

use PhpTwinfield\Enums\MeansOfPayment;

trait MeansOfPaymentField
{
    /**
     * Means of payment field
     * Used by: CustomerFinancials, SupplierFinancials
     *
     * @var MeansOfPayment|null
     */
    private $meansOfPayment;

    public function getMeansOfPayment(): ?MeansOfPayment
    {
        return $this->meansOfPayment;
    }

    /**
     * @param MeansOfPayment|null $meansOfPayment
     * @return $this
     */
    public function setMeansOfPayment(?MeansOfPayment $meansOfPayment): self
    {
        $this->meansOfPayment = $meansOfPayment;
        return $this;
    }
}