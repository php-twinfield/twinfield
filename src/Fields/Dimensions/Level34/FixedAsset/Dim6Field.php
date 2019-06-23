<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

/**
 * The dimension
 * Used by: FixedAssetTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim6Field
{
    /**
     * @var object|null
     */
    private $dim6;

    public function getDim6()
    {
        return $this->dim6;
    }

    /**
     * @return $this
     */
    public function setDim6($dim6): self
    {
        $this->dim6 = $dim6;
        return $this;
    }
}
