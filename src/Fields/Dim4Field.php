<?php

namespace PhpTwinfield\Fields;

/**
 * The dimension
 * Used by: ElectronicBankStatementTransaction, FixedAssetTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim4Field
{
    /**
     * @var object|null
     */
    private $dim4;

    public function getDim4()
    {
        return $this->dim4;
    }

    /**
     * @return $this
     */
    public function setDim4($dim4): self
    {
        $this->dim4 = $dim4;
        return $this;
    }
}
