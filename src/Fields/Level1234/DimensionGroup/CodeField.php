<?php

namespace PhpTwinfield\Fields\Level1234\DimensionGroup;

use PhpTwinfield\Dummy;

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

    public function getCodeToString(): ?string
    {
        if ($this->getCode() != null) {
            return $this->code->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string|null $codeCode
     * @return $this
     * @throws Exception
     */
    public function setCodeFromString(?string $codeCode)
    {
        $code = new Dummy();
        $code->setCode($codeCode);
        return $this->setCode($code);
    }
}
