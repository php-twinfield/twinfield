<?php

namespace PhpTwinfield\Fields\Dimensions;

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
}