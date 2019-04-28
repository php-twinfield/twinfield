<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodePercentage
{
    private $date;
    private $percentage;
    private $name;
    private $shortName;
    private $accounts = [];

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
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

    public function getAccounts()
    {
        return $this->accounts;
    }

    public function addAccount(VatCodeAccount $account)
    {
        $this->accounts[$account->getID()] = $account;
        return $this;
    }

    public function removeAccount($index)
    {
        if (array_key_exists($index, $this->accounts)) {
            unset($this->accounts[$index]);
            return true;
        } else {
            return false;
        }
    }
}
