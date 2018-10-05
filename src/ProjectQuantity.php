<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects
 */
class ProjectQuantity
{
    private $label;
    private $rate;
    private $billable;
    private $mandatory;

    /**
     * Get the label attribute
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return ProjectQuantity
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get the rate attribute
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return ProjectQuantity
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Get the billable attribute
     * @return mixed
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * @param bool $billable
     * @return ProjectQuantity
     */
    public function setBillable(bool $billable)
    {
        $this->billable = $billable;
        return $this;
    }

    /**
     * Get the mandatory attribute
     * @return mixed
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param bool $mandatory
     * @return ProjectQuantity
     */
    public function setMandatory(bool $mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }
}
