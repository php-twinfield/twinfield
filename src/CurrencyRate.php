<?php

namespace PhpTwinfield;

/**
 * @see https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
 * @todo Add documentation and typehints to all properties.
 */
class CurrencyRate
{
    private $status;
    private $startdate;
    private $rate;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStartdate()
    {
        return $this->startdate;
    }

    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
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
