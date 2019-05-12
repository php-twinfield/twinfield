<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Signature date field
 * Used by: CustomerCollectMandate
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
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
     * @return string|null
     */
    public function getSignatureDateToString(): ?string
    {
        if ($this->getSignatureDate() != null) {
            return Util::formatDate($this->getSignatureDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $signatureDateString
     * @return $this
     * @throws Exception
     */
    public function setSignatureDateFromString(?string $signatureDateString)
    {
        if ((bool)strtotime($signatureDateString)) {
            return $this->setSignatureDate(Util::parseDate($signatureDateString));
        } else {
            return $this->setSignatureDate(null);
        }
    }
}