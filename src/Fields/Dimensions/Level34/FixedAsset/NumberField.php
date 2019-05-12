<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

trait NumberField
{
    /**
     * Number field
     * Used by: FixedAssetTransactionLine
     *
     * @var int|null
     */
    private $number;

    /**
     * @return null|int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param null|int $number
     * @return $this
     */
    public function setNumber(?int $number): self
    {
        $this->number = $number;
        return $this;
    }
}