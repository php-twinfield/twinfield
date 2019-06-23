<?php

namespace PhpTwinfield\Fields\Transaction;

use PhpTwinfield\Enums\Regime;

trait RegimeField
{
    /**
     * Regime field
     * Used by: JournalTransaction
     *
     * @var Regime|null
     */
    private $regime;

    public function getRegime(): ?Regime
    {
        return $this->regime;
    }

    /**
     * @param Regime|null $regime
     * @return $this
     */
    public function setRegime(?Regime $regime): self
    {
        $this->regime = $regime;
        return $this;
    }
}