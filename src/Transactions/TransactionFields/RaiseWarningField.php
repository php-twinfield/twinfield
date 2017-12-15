<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait RaiseWarningField
{
    /**
     * @var bool|null Should warnings be given or not?
     */
    private $raiseWarning;

    /**
     * @return bool|null
     */
    public function getRaiseWarning(): ?bool
    {
        return $this->raiseWarning;
    }

    /**
     * @param bool|null $raiseWarning
     * @return $this
     */
    public function setRaiseWarning(?bool $raiseWarning)
    {
        $this->raiseWarning = $raiseWarning;

        return $this;
    }
}