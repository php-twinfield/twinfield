<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

/**
 * Signature date field
 * Used by: CustomerCollectMandate
 *
 * @package PhpTwinfield\Traits
 */
trait SignatureDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $signatureDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getSignatureDate(): ?\DateTimeInterface
    {
        return $this->signatureDate;
    }

    /**
     * @param \DateTimeInterface|null $signatureDate
     * @return $this
     */
    public function setSignatureDate(?\DateTimeInterface $signatureDate)
    {
        $this->signatureDate = $signatureDate;
        return $this;
    }
}