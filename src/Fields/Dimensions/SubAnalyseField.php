<?php

namespace PhpTwinfield\Fields\Dimensions;

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
}
