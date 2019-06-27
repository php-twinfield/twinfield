<?php

namespace PhpTwinfield\Fields;

trait FreeText2Field
{
    /**
     * Free text 2 field
     * Used by: BaseTransaction, CustomerCreditManagement, FixedAssetFixedAssets, InvoiceLine
     *
     * @var string|null
     */
    private $freeText2;

    /**
     * @return null|string
     */
    public function getFreeText2(): ?string
    {
        return $this->freeText2;
    }

    /**
     * @param null|string $freeText2
     * @return $this
     */
    public function setFreeText2(?string $freeText2): self
    {
        $this->freeText2 = $freeText2;
        return $this;
    }
}