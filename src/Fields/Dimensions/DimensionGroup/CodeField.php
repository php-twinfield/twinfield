<?php

namespace PhpTwinfield\Fields\Dimensions\DimensionGroup;

/**
 * The dimension
 * Used by: DimensionGroupDimension
 *
 * @package PhpTwinfield\Traits
 */
trait CodeField
{
    /**
     * @var object|null
     */
    private $code;

    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * @return $this
     */
    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }
}
