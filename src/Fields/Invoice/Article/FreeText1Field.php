<?php

namespace PhpTwinfield\Fields\Invoice\Article;

use PhpTwinfield\GeneralLedger;

/**
 * The general ledger
 * Used by: ArticleLine
 *
 * @package PhpTwinfield\Traits
 */
trait FreeText1Field
{
    /**
     * @var GeneralLedger|null
     */
    private $freeText1;

    public function getFreeText1(): ?GeneralLedger
    {
        return $this->freeText1;
    }

    /**
     * @return $this
     */
    public function setFreeText1(?GeneralLedger $freeText1): self
    {
        $this->freeText1 = $freeText1;
        return $this;
    }
}
