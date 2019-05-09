<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Enums\Status;

trait StatusField
{
    /**
     * Status field
     *
     * @var Status|null
     */
    private $status;

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status|null $status
     * @return $this
     */
    public function setStatus(?Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string|null $statusString
     * @return $this
     * @throws Exception
     */
    public function setStatusFromString(?string $statusString)
    {
        return $this->setStatus(new Status((string)$statusString));
    }
}