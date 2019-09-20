<?php

namespace PhpTwinfield;

/**
 * Class GeneralLedgerAccount
 */
class GeneralLedgerAccount
{
    /**
     * @var string The code of the General Ledger Account.
     */
    private $code;

    /**
     * @var string The name of the General Ledger Account.
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
