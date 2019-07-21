<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait LastDepreciationField
{
    /**
     * Last depreciation in 'YYYY/PP' format (e.g. '2013/05')
     * Used by: FixedAssetFixedAssets
     *
     * @var string
     */
    private $lastDepreciation;

    /**
     * @return string|null
     */
    public function getLastDepreciation(): ?string
    {
        return $this->lastDepreciation;
    }

    /**
     * @param string|null $lastDepreciation
     * @return $this
     */
    public function setLastDepreciation(?string $lastDepreciation): self
    {
        if (!preg_match("!\\d{4}/\\d{1,2}!", $lastDepreciation)) {
            $lastDepreciation = '';
        }

        $this->lastDepreciation = $lastDepreciation;

        return $this;
    }
}
