<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

use PhpTwinfield\CostCenter;

/**
 * The cost center
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension2Field
{
    /**
     * @var CostCenter|null
     */
    private $dimension2;

    public function getDimension2(): ?CostCenter
    {
        return $this->dimension2;
    }

    public function getDimension2ToCode(): ?string
    {
        if ($this->getDimension2() != null) {
            return $this->dimension2->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDimension2(?CostCenter $dimension2): self
    {
        $this->dimension2 = $dimension2;
        return $this;
    }

    /**
     * @param string|null $dimension2Code
     * @return $this
     * @throws Exception
     */
    public function setDimension2FromCode(?string $dimension2Code)
    {
        $dimension2 = new CostCenter();
        $dimension2->setCode($dimension2Code);
        return $this->setDimension2($dimension2);
    }
}