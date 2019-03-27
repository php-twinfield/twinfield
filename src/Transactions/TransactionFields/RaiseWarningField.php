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
    public function getRaiseWarning()
    {
        return $this->raiseWarning;
    }

    /**
     * @param bool|null $raiseWarning
     * @return $this
     */
    public function setRaiseWarning($raiseWarning = null)
    {
        $this->raiseWarning = $raiseWarning;

        return $this;
    }
}