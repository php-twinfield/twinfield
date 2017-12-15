<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerAddress
{
    public $ID;
    public $type;
    public $default;
    public $name;
    public $contact;
    public $country;
    public $city;
    public $postcode;
    public $telephone;
    public $fax;
    public $email;
    public $field1;
    public $field2;
    public $field3;
    public $field4;
    public $field5;
    public $field6;

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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        $this->default = $default;
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

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getFax()
    {
        return $this->fax;
    }

    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getField1()
    {
        return $this->field1;
    }

    public function setField1($field1)
    {
        $this->field1 = $field1;
        return $this;
    }

    public function getField2()
    {
        return $this->field2;
    }

    public function setField2($field2)
    {
        $this->field2 = $field2;
        return $this;
    }

    public function getField3()
    {
        return $this->field3;
    }

    public function setField3($field3)
    {
        $this->field3 = $field3;
        return $this;
    }

    public function getField4()
    {
        return $this->field4;
    }

    public function setField4($field4)
    {
        $this->field4 = $field4;
        return $this;
    }

    public function getField5()
    {
        return $this->field5;
    }

    public function setField5($field5)
    {
        $this->field5 = $field5;
        return $this;
    }

    public function getField6()
    {
        return $this->field6;
    }

    public function setField6($field6)
    {
        $this->field6 = $field6;
        return $this;
    }
}
