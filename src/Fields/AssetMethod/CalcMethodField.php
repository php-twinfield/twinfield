<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\Enums\CalcMethod;

trait CalcMethodField
{
    /**
     * Calc method field
     * Used by: AssetMethod
     *
     * @var CalcMethod|null
     */
    private $calcMethod;

    public function getCalcMethod(): ?CalcMethod
    {
        return $this->calcMethod;
    }

    /**
     * @param CalcMethod|null $calcMethod
     * @return $this
     */
    public function setCalcMethod(?CalcMethod $calcMethod): self
    {
        $this->calcMethod = $calcMethod;
        return $this;
    }

    /**
     * @param string|null $calcMethodString
     * @return $this
     * @throws Exception
     */
    public function setCalcMethodFromString(?string $calcMethodString)
    {
        return $this->setCalcMethod(new CalcMethod((string)$calcMethodString));
    }
}