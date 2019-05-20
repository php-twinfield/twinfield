<?php

namespace PhpTwinfield\Fields;

trait FreeText3Field
{
    /**
     * Free text 3 field
     * Used by: BaseTransaction, CustomerCreditManagement, FixedAssetFixedAssets, InvoiceLine
     *
     * @var string|null
     */
    private $freeText3;

    /**
     * @return null|string
     */
    public function getFreetext3(): ?string
    {
        return $this->freeText3;
    }

    /**
     * @param null|string $freeText3
     * @return $this
     */
    public function setFreetext3(?string $freeText3): self
    {
        $this->freeText3 = $freeText3;
        return $this;
    }
}