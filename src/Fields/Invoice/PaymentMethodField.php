<?php

namespace PhpTwinfield\Fields\Invoice;

use PhpTwinfield\Enums\PaymentMethod;

trait PaymentMethodField
{
    /**
     * Payment method field
     * Used by: Invoice
     *
     * @var PaymentMethod|null
     */
    private $paymentMethod;

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethod|null $paymentMethod
     * @return $this
     */
    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @param string|null $paymentMethodString
     * @return $this
     * @throws Exception
     */
    public function setPaymentMethodFromString(?string $paymentMethodString)
    {
        return $this->setPaymentMethod(new PaymentMethod((string)$paymentMethodString));
    }
}