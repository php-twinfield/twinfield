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

    public function getAssetsToActivateToString(): ?string
    {
        if ($this->getAssetsToActivate() != null) {
            return $this->assetsToActivate->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setAssetsToActivate(?GeneralLedger $assetsToActivate): self
    {
        $this->assetsToActivate = $assetsToActivate;
        return $this;
    }

    /**
     * @param string|null $assetsToActivateString
     * @return $this
     * @throws Exception
     */
    public function setAssetsToActivateFromString(?string $assetsToActivateString)
    {
        $assetsToActivate = new GeneralLedger();
        $assetsToActivate->setCode($assetsToActivateString);
        return $this->setAssetsToActivate($assetsToActivate);
    }
}