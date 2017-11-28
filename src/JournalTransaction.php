<?php

namespace PhpTwinfield;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions
 */
class JournalTransaction extends BaseTransaction
{
    public const REGIME_GENERIC    = 'Generic';
    public const REGIME_FISCAL     = 'Fiscal';
    public const REGIME_COMMERCIAL = 'Commercial';
    public const REGIME_ECONOMIC   = 'Economic';

    /**
     * @var string|null One of the self::REGIME_* constants. You can post transactions as 'Fiscal', 'Commercial',
     *                  'Economic' or 'Generic'. The default regime is 'Generic'. 'Generic' means that the transaction
     *                  is visible for all regimes.
     */
    private $regime;

    public function getLineClassName(): string
    {
        return JournalTransactionLine::class;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(?string $regime): BaseTransaction
    {
        $this->regime = $regime;

        return $this;
    }
}
