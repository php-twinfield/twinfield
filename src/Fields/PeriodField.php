<?php

namespace PhpTwinfield\Fields;

trait PeriodField
{
    /**
     * Period in 'YYYY/PP' format (e.g. '2013/05'). If this tag is not included or if it is left empty, the period is
     * determined by the system based on the provided transaction date.
     * Used by: FixedAssetTransactionLine, Invoice
     *
     * @var string
     */
    private $period;

    /**
     * @return string|null
     */
    public function getPeriod(): ?string
    {
        return $this->period;
    }

    /**
     * @param string|null $period
     * @return $this
     */
    public function setPeriod(?string $period): self
    {
        if (!preg_match("!\\d{4}/\\d{1,2}!", $period)) {
            throw new \InvalidArgumentException("Period must be in YYYY/PP format (got: {$period}.");
        }

        $this->period = $period;

        return $this;
    }
}
