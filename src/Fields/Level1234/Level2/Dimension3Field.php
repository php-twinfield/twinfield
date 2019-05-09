<?php

namespace PhpTwinfield\Fields\Level1234\Level2;

use PhpTwinfield\Dummy;

/**
 * The dimension
 * Used by: CustomerLine, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait Dimension3Field
{
    /**
     * @var object|null
     */
    private $dimension3;

    public function getDimension3()
    {
        return $this->dimension3;
    }

    public function getDimension3ToCode(): ?string
    {
        if ($this->getDimension3() != null) {
            return $this->dimension3->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDimension3($dimension3): self
    {
        $this->dimension3 = $dimension3;
        return $this;
    }

    /**
     * @param string|null $dimension3Code
     * @return $this
     * @throws Exception
     */
    public function setDimension3FromCode(?string $dimension3Code)
    {
        $dimension3 = new Dummy();
        $dimension3->setCode($dimension3Code);
        return $this->setDimension3($dimension3);
    }
}