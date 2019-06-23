<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use PhpTwinfield\AssetMethod;

/**
 * The asset method
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 */
trait MethodField
{
    /**
     * @var AssetMethod|null
     */
    private $method;

    public function getMethod(): ?AssetMethod
    {
        return $this->method;
    }

    /**
     * @return $this
     */
    public function setMethod(?AssetMethod $method): self
    {
        $this->method = $method;
        return $this;
    }
}
