<?php

namespace PhpTwinfield\Fields\Dimensions;

use PhpTwinfield\Dummy;

/**
 * The dimension
 * Used by: CustomerFinancials, FixedAssetFinancials, SupplierFinancials
 *
 * @package PhpTwinfield\Traits
 */
trait SubstituteWithField
{
    /**
     * @var object|null
     */
    private $substituteWith;

    public function getSubstituteWith()
    {
        return $this->substituteWith;
    }

    public function getSubstituteWithToString(): ?string
    {
        if ($this->getSubstituteWith() != null) {
            return $this->substituteWith->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setSubstituteWith($substituteWith): self
    {
        $this->substituteWith = $substituteWith;
        return $this;
    }

    /**
     * @param string|null $substituteWithCode
     * @return $this
     * @throws Exception
     */
    public function setSubstituteWithFromString(?string $substituteWithCode)
    {
        $substituteWith = new Dummy();
        $substituteWith->setCode($substituteWithCode);
        return $this->setSubstituteWith($substituteWith);
    }
}
