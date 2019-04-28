<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
 * @todo Add documentation and typehints to all properties.
 */
class Currency extends BaseObject
{
    use OfficeField;

    private $code;
    private $status;
    private $name;
    private $shortName;
    private $rates = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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

    public function getRates()
    {
        return $this->rates;
    }

    public function addRate(CurrencyRate $rate)
    {
        $this->rates[] = $rate;
        return $this;
    }

    public function removeRate($index)
    {
        if (array_key_exists($index, $this->rates)) {
            unset($this->rates[$index]);
            return true;
        } else {
            return false;
        }
    }
}
