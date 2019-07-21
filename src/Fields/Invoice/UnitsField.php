<?php

namespace PhpTwinfield\Fields\Invoice;

trait UnitsField
{
    /**
     * Units field
     * Used by: ArticleLine, InvoiceLine
     *
     * @var int|null
     */
    private $units;

    /**
     * @return null|int
     */
    public function getUnits(): ?int
    {
        return $this->units;
    }

    /**
     * @param null|int $units
     * @return $this
     */
    public function setUnits(?int $units): self
    {
        $this->units = $units;
        return $this;
    }
}
