<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

use PhpTwinfield\Dummy;

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

    public function getDim6ToCode(): ?string
    {
        if ($this->getDim6() != null) {
            return $this->dim6->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim6($dim6): self
    {
        $this->dim6 = $dim6;
        return $this;
    }
    
    /**
     * @param string|null $dim6Code
     * @return $this
     * @throws Exception
     */
    public function setDim6FromCode(?string $dim6Code)
    {
        $dim6 = new Dummy();
        $dim6->setCode($dim6Code);
        return $this->setDim6($dim6);
    }
}
