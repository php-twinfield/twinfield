<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

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
    public function getFreeText4(): ?string
    {
        return $this->freeText4;
    }

    /**
     * @param null|string $freeText4
     * @return $this
     */
    public function setFreeText4(?string $freeText4): self
    {
        $this->freeText4 = $freeText4;
        return $this;
    }
}
