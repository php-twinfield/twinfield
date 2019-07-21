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

    /**
     * @return $this
     */
    public function setFreeText2(?CostCenter $freeText2): self
    {
        $this->freeText2 = $freeText2;
        return $this;
    }
}
