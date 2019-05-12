<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

/**
 * The dimension
 * Used by: FixedAssetTransactionLine
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
}
