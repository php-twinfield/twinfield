<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

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

    public function getDim6ToString(): ?string
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
     * @param string|null $dim6String
     * @return $this
     * @throws Exception
     */
    public function setDim6FromString(?string $dim6String)
    {
        $dim6 = new Dummy();
        $dim6->setCode($dim6String);
        return $this->setDim6($dim6);
    }
}
