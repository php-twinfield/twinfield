<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * Class Rate
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Rate extends BaseObject
{
    use OfficeField;

    private $code;
    private $currency;
    private $name;
    private $shortName;
    private $status;
    private $type;
    private $unit;
    private $ratechanges = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function getRateChanges()
    {
        return $this->ratechanges;
    }

    public function addRateChange(RateRateChange $ratechange)
    {
        $this->ratechanges[$ratechange->getID()] = $ratechange;
        return $this;
    }

    public function removeRateChange($index)
    {
        if (array_key_exists($index, $this->ratechanges)) {
            unset($this->ratechanges[$index]);
            return true;
        } else {
            return false;
        }
    }
}
