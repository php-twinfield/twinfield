<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension1Field
{
    /**
     * @var GeneralLedger|null
     */
    private $dimension1;

    public function getDimension1(): ?GeneralLedger
    {
        return $this->dimension1;
    }

    public function getDimension1ToString(): ?string
    {
        if ($this->getDimension1() != null) {
            return $this->dimension1->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDimension1(?GeneralLedger $dimension1): self
    {
        $this->dimension1 = $dimension1;
        return $this;
    }

    /**
     * @param string|null $dimension1Code
     * @return $this
     * @throws Exception
     */
    public function setDimension1FromString(?string $dimension1Code)
    {
        $dimension1 = new GeneralLedger();
        $dimension1->setCode($dimension1Code);
        return $this->setDimension1($dimension1);
    }
}