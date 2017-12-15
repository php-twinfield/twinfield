<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles
 * @todo Add documentation and typehints to all properties.
 */
class ArticleLine
{
    public $ID;
    public $status;
    public $inUse;
    public $unitsPriceExcl;
    public $unitsPriceInc;
    public $units;
    public $name;
    public $shortName;
    public $subCode;
    public $freeText1;

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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getInUse()
    {
        return $this->inUse;
    }

    public function setInUse($inUse)
    {
        $this->inUse = $inUse;
        return $this;
    }

    public function getUnitsPriceInc()
    {
        return $this->unitsPriceInc;
    }

    public function setUnitsPriceInc($unitsPriceInc)
    {
        $this->unitsPriceInc = $unitsPriceInc;
        return $this;
    }

    public function getUnitsPriceExcl ()
    {
        return $this->unitsPriceExcl;
    }

    public function setUnitsPriceExcl($unitsPriceExcl)
    {
        $this->unitsPriceExcl = $unitsPriceExcl;
        return $this;
    }

    public function getUnits()
    {
        return $this->units;
    }

    public function setUnits($units)
    {
        $this->units = $units;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getSubCode()
    {
        return $this->subCode;
    }

    public function setSubCode($subCode)
    {
        $this->subCode = $subCode;
        return $this;
    }

    public function getFreeText1()
    {
        return $this->freeText1;
    }

    public function setFreeText1($freeText1)
    {
        $this->freeText1 = $freeText1;
        return $this;
    }
}
