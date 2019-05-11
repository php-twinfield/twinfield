<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

use PhpTwinfield\Dummy;

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

    public function getDim5ToString(): ?string
    {
        if ($this->getDim5() != null) {
            return $this->dim5->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim5($dim5): self
    {
        $this->dim5 = $dim5;
        return $this;
    }
    
    /**
     * @param string|null $dim5Code
     * @return $this
     * @throws Exception
     */
    public function setDim5FromString(?string $dim5Code)
    {
        $dim5 = new Dummy();
        $dim5->setCode($dim5Code);
        return $this->setDim5($dim5);
    }
}
