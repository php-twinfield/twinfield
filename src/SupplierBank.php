<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers
 * @todo Add documentation and typehints to all properties.
 */
class SupplierBank
{
    public $ID;             # integer      Sequence number of the bank account line.
    public $default;        # true/false   Is this the default bank account, only one default bank account is possible.
    public $ascription;     # string(40)   Account holder.
    public $accountnumber;  # string(40)   Account number.
    public $addressField2;  # string(128)  Bank address.
    public $addressField3;  # string(128)  Bank address number.
    public $bankname;       # string(40)   Bank name.
    public $biccode;        # string(16)   BIC code.
    public $city;           # string(40)   City.
    public $country;        # string(2)    Bank country code. The ISO country codes are used.
    public $iban;           # string(40)   IBAN account number.
    public $natbiccode;     # string(20)   National bank code.
    public $postcode;       # string(16)   Postcode.
    public $state;          # string(40)   State.

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

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    public function getAscription()
    {
        return $this->ascription;
    }

    public function setAscription($ascription)
    {
        $this->ascription = $ascription;
        return $this;
    }

    public function getAccountnumber()
    {
        return $this->accountnumber;
    }

    public function setAccountnumber($accountnumber)
    {
        $this->accountnumber = $accountnumber;
        return $this;
    }

    public function getAddressField2()
    {
        return $this->addressField2;
    }

    public function setAddressField2($addressField2)
    {
        $this->addressField2 = $addressField2;
        return $this;
    }

    public function getAddressField3()
    {
        return $this->addressField3;
    }

    public function setAddressField3($addressField3)
    {
        $this->addressField3 = $addressField3;
        return $this;
    }

    public function getBankname()
    {
        return $this->bankname;
    }

    public function setBankname($bankname)
    {
        $this->bankname = $bankname;
        return $this;
    }

    public function getBiccode()
    {
        return $this->biccode;
    }

    public function setBiccode($biccode)
    {
        $this->biccode = $biccode;
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

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getIban()
    {
        return $this->iban;
    }

    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    public function getNatbiccode()
    {
        return $this->natbiccode;
    }

    public function setNatbiccode($natbiccode)
    {
        $this->natbiccode = $natbiccode;
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

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
}
