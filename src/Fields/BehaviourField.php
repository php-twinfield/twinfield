<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Enums\Behaviour;

trait BehaviourField
{
    /**
     * Behaviour field
     * Used by: Activity, CostCenter, Customer, FixedAsset, GeneralLedger, Project, Supplier
     *
     * @var Behaviour|null
     */
    private $behaviour;

    public function getBehaviour(): ?Behaviour
    {
        return $this->behaviour;
    }

    /**
     * @param Behaviour|null $behaviour
     * @return $this
     */
    public function setBehaviour(?Behaviour $behaviour): self
    {
        $this->behaviour = $behaviour;
        return $this;
    }
}
