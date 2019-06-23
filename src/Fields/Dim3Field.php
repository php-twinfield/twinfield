<?php

namespace PhpTwinfield\Fields;

/**
 * The dimension
 * Used by: BaseTransactionLine, FixedAssetTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim3Field
{
    /**
     * @var object|null
     */
    private $dim3;

    public function getDim3()
    {
        return $this->dim3;
    }

    /**
     * @return $this
     */
    public function setDim3($dim3): self
    {
        $this->dim3 = $dim3;
        return $this;
    }
}
