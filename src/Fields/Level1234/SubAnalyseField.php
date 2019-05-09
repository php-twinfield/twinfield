<?php

namespace PhpTwinfield\Fields\Level1234;

use PhpTwinfield\Enums\SubAnalyse;

trait SubAnalyseField
{
    /**
     * Sub analyse field
     * Used by: CustomerFinancials, FixedAssetFinancials, GeneralLedgerFinancials, SupplierFinancials
     *
     * @var SubAnalyse|null
     */
    private $subAnalyse;

    public function getSubAnalyse(): ?SubAnalyse
    {
        return $this->subAnalyse;
    }

    /**
     * @param SubAnalyse|null $subAnalyse
     * @return $this
     */
    public function setSubAnalyse(?SubAnalyse $subAnalyse): self
    {
        $this->subAnalyse = $subAnalyse;
        return $this;
    }

    /**
     * @param string|null $subAnalyseString
     * @return $this
     * @throws Exception
     */
    public function setSubAnalyseFromString(?string $subAnalyseString)
    {
        return $this->setSubAnalyse(new SubAnalyse((string)$subAnalyseString));
    }
}