<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\Enums\DepreciateReconciliation;

trait DepreciateReconciliationField
{
    /**
     * Depreciate reconciliation field
     * Used by: AssetMethod
     *
     * @var DepreciateReconciliation|null
     */
    private $depreciateReconciliation;

    public function getDepreciateReconciliation(): ?DepreciateReconciliation
    {
        return $this->depreciateReconciliation;
    }

    /**
     * @param DepreciateReconciliation|null $depreciateReconciliation
     * @return $this
     */
    public function setDepreciateReconciliation(?DepreciateReconciliation $depreciateReconciliation): self
    {
        $this->depreciateReconciliation = $depreciateReconciliation;
        return $this;
    }

    /**
     * @param string|null $depreciateReconciliationString
     * @return $this
     * @throws Exception
     */
    public function setDepreciateReconciliationFromString(?string $depreciateReconciliationString)
    {
        return $this->setDepreciateReconciliation(new DepreciateReconciliation((string)$depreciateReconciliationString));
    }
}