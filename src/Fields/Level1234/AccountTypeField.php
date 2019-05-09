<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\Enums\AccountType;

trait AccountTypeField
{
    /**
     * Account type field
     * Used by: CustomerFinancials, FixedAssetFinancials, GeneralLedgerFinancials, SupplierFinancials
     *
     * @var AccountType|null
     */
    private $accountType;

    public function getAccountType(): ?AccountType
    {
        return $this->accountType;
    }

    /**
     * @param AccountType|null $accountType
     * @return $this
     */
    public function setAccountType(?AccountType $accountType): self
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * @param string|null $accountTypeString
     * @return $this
     * @throws Exception
     */
    public function setAccountTypeFromString(?string $accountTypeString)
    {
        return $this->setAccountType(new AccountType((string)$accountTypeString));
    }
}