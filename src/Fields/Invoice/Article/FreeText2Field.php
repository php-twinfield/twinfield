<?php

namespace PhpTwinfield\Fields\Invoice\Article;

use PhpTwinfield\CostCenter;

/**
 * The cost center
 * Used by: ArticleLine
 *
 * @package PhpTwinfield\Traits
 */
trait FreeText2Field
{
    /**
     * @var CostCenter|null
     */
    private $freeText2;

    public function getFreeText2(): ?CostCenter
    {
        return $this->freeText2;
    }

    public function getFreeText2ToString(): ?string
    {
        if ($this->getFreeText2() != null) {
            return $this->freeText2->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setFreeText2(?CostCenter $freeText2): self
    {
        $this->freeText2 = $freeText2;
        return $this;
    }

    /**
     * @param string|null $freeText2Code
     * @return $this
     * @throws Exception
     */
    public function setFreeText2FromString(?string $freeText2Code)
    {
        $freeText2 = new CostCenter();
        $freeText2->setCode($freeText2Code);
        return $this->setFreeText2($freeText2);
    }
}
