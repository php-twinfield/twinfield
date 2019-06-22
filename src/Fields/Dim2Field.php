<?php

namespace PhpTwinfield\Fields;

/**
 * The dimension
 * Used by: BaseTransactionLine, FixedAssetTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim2Field
{
    /**
     * @var object|null
     */
    private $dim2;

    public function getDim2()
    {
        return $this->dim2;
    }

    /**
     * @return $this
     */
    public function setDim2($dim2): self
    {
        $this->dim2 = $dim2;
        return $this;
    }
}
