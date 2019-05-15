<?php

namespace PhpTwinfield\Fields\User;

trait FileManagerQuotaField
{
    /**
     * File manager quota field
     * Used by: User
     *
     * @var int|null
     */
    private $fileManagerQuota;

    /**
     * @return null|int
     */
    public function getFileManagerQuota(): ?int
    {
        return $this->fileManagerQuota;
    }

    /**
     * @param null|int $fileManagerQuota
     * @return $this
     */
    public function setFileManagerQuota(?int $fileManagerQuota): self
    {
        $this->fileManagerQuota = $fileManagerQuota;
        return $this;
    }
}