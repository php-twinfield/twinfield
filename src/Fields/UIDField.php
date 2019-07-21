<?php

namespace PhpTwinfield\Fields;

trait UIDField
{
    /**
     * UID field
     * Used by: Activity, AssetMethod, CostCenter, Customer, FixedAsset, GeneralLedger, Project, Supplier, VatCode
     *
     * @var string|null
     */
    private $UID;

    /**
     * @return null|string
     */
    public function getUID(): ?string
    {
        return $this->UID;
    }

    /**
     * @param null|string $UID
     * @return $this
     */
    public function setUID(?string $UID): self
    {
        $this->UID = $UID;
        return $this;
    }
}
