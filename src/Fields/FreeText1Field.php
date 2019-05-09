<?php

namespace PhpTwinfield\Fields;

trait FreeText1Field
{
    /**
     * Free text 1 field
     * Used by: FixedAssetFixedAssets, InvoiceLine
     *
     * @var string|null
     */
    private $freeText1;

    /**
     * @return null|string
     */
    public function getFreetext1(): ?string
    {
        return $this->freeText1;
    }

    /**
     * @param null|string $freeText1
     * @return $this
     */
    public function setFreetext1(?string $freeText1): self
    {
        $this->freeText1 = $freeText1;
        return $this;
    }
}