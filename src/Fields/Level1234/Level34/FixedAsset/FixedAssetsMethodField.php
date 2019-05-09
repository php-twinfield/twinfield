<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

use PhpTwinfield\AssetMethod;

/**
 * The fixed asset method
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 */
trait FixedAssetsMethodField
{
    /**
     * @var AssetMethod|null
     */
    private $method;

    public function getMethod(): ?AssetMethod
    {
        return $this->method;
    }

    public function getMethodToCode(): ?string
    {
        if ($this->getMethod() != null) {
            return $this->method->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setMethod(?AssetMethod $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string|null $methodCode
     * @return $this
     * @throws Exception
     */
    public function setMethodFromCode(?string $methodCode)
    {
        $method = new AssetMethod();
        $method->setCode($methodCode);
        return $this->setMethod($method);
    }
}
