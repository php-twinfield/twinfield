<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionTypeAddress
{
    private $label1;
    private $label2;
    private $label3;
    private $label4;
    private $label5;
    private $label6;

    public function getLabel1()
    {
        return $this->label1;
    }

    public function setLabel1($label1)
    {
        $this->label1 = $label1;
        return $this;
    }

    public function getLabel2()
    {
        return $this->label2;
    }

    public function setLabel2($label2)
    {
        $this->label2 = $label2;
        return $this;
    }

    public function getLabel3()
    {
        return $this->label3;
    }

    public function setLabel3($label3)
    {
        $this->label3 = $label3;
        return $this;
    }

    public function getLabel4()
    {
        return $this->label4;
    }

    public function setLabel4($label4)
    {
        $this->label4 = $label4;
        return $this;
    }

    public function getLabel5()
    {
        return $this->label5;
    }

    public function setLabel5($label5)
    {
        $this->label5 = $label5;
        return $this;
    }

    public function getLabel6()
    {
        return $this->label6;
    }

    public function setLabel6($label6)
    {
        $this->label6 = $label6;
        return $this;
    }
}
