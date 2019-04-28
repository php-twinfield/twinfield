<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates
 * @todo Add documentation and typehints to all properties.
 */
class RateRateChange
{
    private $ID;
    private $lastused;
    private $status;
    private $begindate;
    private $enddate;
    private $internalrate;
    private $externalrate;

    public function __construct()
    {
        $this->ID = uniqid();
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    public function getLastUsed()
    {
        return $this->lastused;
    }

    public function setLastUsed($lastused)
    {
        $this->lastused = $lastused;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getBeginDate()
    {
        return $this->begindate;
    }

    public function setBeginDate($begindate)
    {
        $this->begindate = $begindate;
        return $this;
    }

    public function getEndDate()
    {
        return $this->enddate;
    }

    public function setEndDate($enddate)
    {
        $this->enddate = $enddate;
        return $this;
    }

    public function getInternalRate()
    {
        return $this->internalrate;
    }

    public function setInternalRate($internalrate)
    {
        $this->internalrate = $internalrate;
        return $this;
    }

    public function getExternalRate()
    {
        return $this->externalrate;
    }

    public function setExternalRate($externalrate)
    {
        $this->externalrate = $externalrate;
        return $this;
    }
}
