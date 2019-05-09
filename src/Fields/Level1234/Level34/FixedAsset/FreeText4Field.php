<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait FreeText4Field
{
    /**
     * Free text 4 field
     * Used by: FixedAssetFixedAssets
     *
     * @var string|null
     */
    private $freeText4;

    /**
     * @return null|string
     */
    public function getFreetext4(): ?string
    {
        return $this->freeText4;
    }

    /**
     * @param null|string $freeText4
     * @return $this
     */
    public function setFreetext4(?string $freeText4): self
    {
        $this->freeText4 = $freeText4;
        return $this;
    }
}