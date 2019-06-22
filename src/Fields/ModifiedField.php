<?php

namespace PhpTwinfield\Fields;

/**
 * Modified field
 * Used by: AssetMethod, Office, Rate, User, VatCode
 *
 * @package PhpTwinfield\Traits
 */
trait ModifiedField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $modified;

    /**
     * @return \DateTimeInterface|null
     */
    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * @param \DateTimeInterface|null $modified
     * @return $this
     */
    public function setModified(?\DateTimeInterface $modified)
    {
        $this->modified = $modified;
        return $this;
    }
}