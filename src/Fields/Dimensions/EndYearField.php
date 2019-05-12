<?php

namespace PhpTwinfield\Fields\Dimensions;

trait EndYearField
{
    /**
     * End year field
     * Used by: Customer, GeneralLedger, Supplier
     *
     * @var int|null
     */
    private $endYear;

    /**
     * @return null|int
     */
    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    /**
     * @param null|int $endYear
     * @return $this
     */
    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;
        return $this;
    }
}