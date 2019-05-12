<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait LineField
{
    /**
     * Line field
     * Used by: FixedAssetTransactionLine
     *
     * @var int|null
     */
    private $line;

    /**
     * @return null|int
     */
    public function getLine(): ?int
    {
        return $this->line;
    }

    /**
     * @param null|int $line
     * @return $this
     */
    public function setLine(?int $line): self
    {
        $this->line = $line;
        return $this;
    }
}