<?php

namespace PhpTwinfield\Fields\User;

trait FileManagerQuotaLockedField
{
    /**
     * File manager quota locked field
     * Used by: User
     *
     * @var bool
     */
    private $fileManagerQuotaLocked;

    /**
     * @return bool
     */
    public function getFileManagerQuotaLocked(): ?bool
    {
        return $this->fileManagerQuotaLocked;
    }

    /**
     * @param bool $fileManagerQuotaLocked
     * @return $this
     */
    public function setFileManagerQuotaLocked(?bool $fileManagerQuotaLocked): self
    {
        $this->fileManagerQuotaLocked = $fileManagerQuotaLocked;
        return $this;
    }
}