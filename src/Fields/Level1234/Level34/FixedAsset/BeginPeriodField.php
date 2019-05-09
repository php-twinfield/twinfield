<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait BeginPeriodField
{
    /**
     * BeginPeriod in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left empty, the beginPeriod is
     * determined by the system based on the provided transaction date.
     * Used by: FixedAssetFixedAssets
     *
     * @var string
     */
    private $beginPeriod;

    /**
     * @return string|null
     */
    public function getBeginPeriod(): ?string
    {
        return $this->beginPeriod;
    }

    /**
     * @param string|null $beginPeriod
     * @return $this
     */
    public function setBeginPeriod(?string $beginPeriod): self
    {
        if (!preg_match("!\\d{4}/\\d{1,2}!", $beginPeriod)) {
            $beginPeriod = '';
        }

        $this->beginPeriod = $beginPeriod;

        return $this;
    }
}
