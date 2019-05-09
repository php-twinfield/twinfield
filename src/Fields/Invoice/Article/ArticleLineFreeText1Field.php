<?php

namespace PhpTwinfield\Fields\Invoice\Article;

use PhpTwinfield\GeneralLedger;

/**
 * The general ledger
 * Used by: ArticleLine
 *
 * @package PhpTwinfield\Traits
 */
trait ArticleLineFreeText1Field
{
    /**
     * @var GeneralLedger|null
     */
    private $freeText1;

    public function getFreeText1(): ?GeneralLedger
    {
        return $this->freeText1;
    }

    public function getFreeText1ToCode(): ?string
    {
        if ($this->getFreeText1() != null) {
            return $this->freeText1->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setFreeText1(?GeneralLedger $freeText1): self
    {
        $this->freeText1 = $freeText1;
        return $this;
    }

    /**
     * @param string|null $freeText1Code
     * @return $this
     * @throws Exception
     */
    public function setFreeText1FromCode(?string $freeText1Code)
    {
        $freeText1 = new GeneralLedger();
        $freeText1->setCode($freeText1Code);
        return $this->setFreeText1($freeText1);
    }
}
