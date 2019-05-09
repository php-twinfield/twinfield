<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait LastDepreciationField
{
    /**
     * LastDepreciation in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left empty, the lastDepreciation is
     * determined by the system based on the provided transaction date.
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
