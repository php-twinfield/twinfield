<?php

namespace PhpTwinfield\Fields\Dimensions;

trait BeginYearField
{
    /**
     * Begin year field
     * Used by: Customer, GeneralLedger, Supplier
     *
     * @var int|null
     */
    private $beginYear;

    /**
     * @return null|int
     */
    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    /**
     * @param null|int $beginYear
     * @return $this
     */
    public function setBeginYear(?int $beginYear): self
    {
        $this->beginYear = $beginYear;
        return $this;
    }
}