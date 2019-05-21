<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Dummy;

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

    public function getDim1ToString(): ?string
    {
        if ($this->getDim1() != null) {
            return $this->dim1->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim1($dim1): self
    {
        $this->dim1 = $dim1;
        return $this;
    }
    
    /**
     * @param string|null $dim1String
     * @return $this
     * @throws Exception
     */
    public function setDim1FromString(?string $dim1String)
    {
        $dim1 = new Dummy();
        $dim1->setCode($dim1String);
        return $this->setDim1($dim1);
    }
}
