<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait AssetsToActivateField
{
    /**
     * @var GeneralLedger|null
     */
    private $assetsToActivate;

    public function getAssetsToActivate(): ?GeneralLedger
    {
        return $this->assetsToActivate;
    }

    /**
     * @return $this
     */
    public function setAssetsToActivate(?GeneralLedger $assetsToActivate): self
    {
        $this->assetsToActivate = $assetsToActivate;
        return $this;
    }
}
