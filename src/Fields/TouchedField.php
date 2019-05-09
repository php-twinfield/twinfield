<?php

namespace PhpTwinfield\Fields;

trait TouchedField
{
    /**
     * Touched field
     * Used by: Activity, AssetMethod, CostCenter, Customer, FixedAsset, GeneralLedger, Project, Supplier, User, VatCode
     *
     * @var int|null
     */
    private $touched;

    /**
     * @return null|int
     */
    public function getTouched(): ?int
    {
        return $this->touched;
    }

    /**
     * @param null|int $touched
     * @return $this
     */
    public function setTouched(?int $touched): self
    {
        $this->touched = $touched;
        return $this;
    }
}