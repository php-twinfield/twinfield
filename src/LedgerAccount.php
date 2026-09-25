<?php

namespace PhpTwinfield;

/**
 * Class LedgerAccount
 *
 * A general ledger account. In Twinfield these are dimensions of type BAS (balance sheet) or PNL (profit and loss).
 */
class LedgerAccount
{
    public const TYPE_BALANCE_SHEET = 'BAS';
    public const TYPE_PROFIT_AND_LOSS = 'PNL';

    /**
     * @var string The code of the ledger account.
     */
    private $code;

    /**
     * @var string The name of the ledger account.
     */
    private $name;

    /**
     * @var string|null The dimension type of the ledger account (BAS or PNL), when known.
     */
    private $type;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function isBalanceSheet(): bool
    {
        return $this->type === self::TYPE_BALANCE_SHEET;
    }

    public function isProfitAndLoss(): bool
    {
        return $this->type === self::TYPE_PROFIT_AND_LOSS;
    }
}
