<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities
 * @todo Add documentation and typehints to all properties.
 */
class ActivityQuantity
{
    private $billable;
    private $label;
    private $mandatory;
    private $rate;

    public function getBillable()
    {
        return $this->billable;
    }

    public function setBillable($billable)
    {
        $this->billable = $billable;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }
}
