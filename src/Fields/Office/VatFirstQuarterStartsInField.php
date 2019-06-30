<?php

namespace PhpTwinfield\Fields\Office;

trait VatFirstQuarterStartsInField
{
    /**
     * Vat first quarter starts in field
     * Used by: Office
     *
     * @var string|null
     */
    private $vatFirstQuarterStartsIn;

    /**
     * @return null|string
     */
    public function getVatFirstQuarterStartsIn(): ?string
    {
        return $this->vatFirstQuarterStartsIn;
    }

    /**
     * @param null|string $vatFirstQuarterStartsIn
     * @return $this
     */
    public function setVatFirstQuarterStartsIn(?string $vatFirstQuarterStartsIn): self
    {
        $this->vatFirstQuarterStartsIn = $vatFirstQuarterStartsIn;
        return $this;
    }
}
