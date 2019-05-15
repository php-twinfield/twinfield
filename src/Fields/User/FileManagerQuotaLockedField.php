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

    public function getFileManagerQuotaLockedToString(): ?string
    {
        return ($this->getFileManagerQuotaLocked()) ? 'true' : 'false';
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

    /**
     * @param string|null $fileManagerQuotaLockedString
     * @return $this
     * @throws Exception
     */
    public function setFileManagerQuotaLockedFromString(?string $fileManagerQuotaLockedString)
    {
        return $this->setFileManagerQuotaLocked(filter_var($fileManagerQuotaLockedString, FILTER_VALIDATE_BOOLEAN));
    }
}