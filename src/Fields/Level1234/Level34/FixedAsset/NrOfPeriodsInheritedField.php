<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

trait NrOfPeriodsInheritedField
{
    /**
     * Nr of periods inherited field
     * Used by: FixedAssetFixedAssets
     *
     * @var bool
     */
    private $nrOfPeriodsInherited;

    /**
     * @return bool
     */
    public function getNrOfPeriodsInherited(): ?bool
    {
        return $this->nrOfPeriodsInherited;
    }

    public function getNrOfPeriodsInheritedToString(): ?string
    {
        return ($this->getNrOfPeriodsInherited()) ? 'true' : 'false';
    }

    /**
     * @param bool $nrOfPeriodsInherited
     * @return $this
     */
    public function setNrOfPeriodsInherited(?bool $nrOfPeriodsInherited): self
    {
        $this->nrOfPeriodsInherited = $nrOfPeriodsInherited;
        return $this;
    }

    /**
     * @param string|null $nrOfPeriodsInheritedString
     * @return $this
     * @throws Exception
     */
    public function setNrOfPeriodsInheritedFromString(?string $nrOfPeriodsInheritedString)
    {
        return $this->setNrOfPeriodsInherited(filter_var($nrOfPeriodsInheritedString, FILTER_VALIDATE_BOOLEAN));
    }
}