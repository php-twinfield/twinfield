<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodeAccount
{
    private $ID;
    private $dim1;
    private $groupcountry;
    private $group;
    private $percentage;
    private $linetype;

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

    public function getDim1()
    {
        return $this->dim1;
    }

    public function setDim1($dim1)
    {
        $this->dim1 = $dim1;
        return $this;
    }

    public function getGroupCountry()
    {
        return $this->groupcountry;
    }

    public function setGroupCountry($groupcountry)
    {
        $this->groupcountry = $groupcountry;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getLineType()
    {
        return $this->linetype;
    }

    public function setLineType($linetype)
    {
        $this->linetype = $linetype;
        return $this;
    }
}
