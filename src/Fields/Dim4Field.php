<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Dummy;

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

    public function getDim4ToString(): ?string
    {
        if ($this->getDim4() != null) {
            return $this->dim4->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim4($dim4): self
    {
        $this->dim4 = $dim4;
        return $this;
    }
    
    /**
     * @param string|null $dim4Code
     * @return $this
     * @throws Exception
     */
    public function setDim4FromString(?string $dim4Code)
    {
        $dim4 = new Dummy();
        $dim4->setCode($dim4Code);
        return $this->setDim4($dim4);
    }
}
