<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

/**
 * The dimension
 * Used by: FixedAssetTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim5Field
{
    /**
     * @var object|null
     */
    private $dim5;

    public function getDim5()
    {
        return $this->dim5;
    }

    /**
     * @return $this
     */
    public function setDim5($dim5): self
    {
        $this->dim5 = $dim5;
        return $this;
    }
}
