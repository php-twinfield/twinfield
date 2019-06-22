<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

/**
 * The dimension
 * Used by: BaseTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dim1Field
{
    /**
     * @var object|null
     */
    private $dim1;

    public function getDim1()
    {
        return $this->dim1;
    }

    /**
     * @return $this
     */
    public function setDim1($dim1): self
    {
        $this->dim1 = $dim1;
        return $this;
    }
}
