<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Dummy;

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

    public function getDim2ToString(): ?string
    {
        if ($this->getDim2() != null) {
            return $this->dim2->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim2($dim2): self
    {
        $this->dim2 = $dim2;
        return $this;
    }
    
    /**
     * @param string|null $dim2Code
     * @return $this
     * @throws Exception
     */
    public function setDim2FromString(?string $dim2Code)
    {
        $dim2 = new Dummy();
        $dim2->setCode($dim2Code);
        return $this->setDim2($dim2);
    }
}
