<?php

namespace PhpTwinfield;

/**
 * Class LedgerAccount
 */
class LedgerAccount
{
    /**
     * @var string The code of the ledger account.
     */
    private $code;

    /**
     * @var string The name of the ledger account.
     */
    private $name;

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
}
