<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait BaselineField
{
    /**
     * Baseline field
     * Used by: JournalTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var int|null
     */
    private $baseline;

    /**
     * @return null|int
     */
    public function getBaseline(): ?int
    {
        return $this->baseline;
    }

    /**
     * @param null|int $baseline
     * @return $this
     */
    public function setBaseline(?int $baseline): self
    {
        $this->baseline = $baseline;
        return $this;
    }
}