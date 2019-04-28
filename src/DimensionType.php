<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionType extends BaseObject
{
    use OfficeField;

    private $code;
    private $mask;
    private $name;
    private $shortName;
    private $status;
    private $levels;
    private $address;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getMask()
    {
        return $this->mask;
    }

    public function setMask($mask)
    {
        $this->mask = $mask;
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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getLevels(): DimensionTypeLevels
    {
        return $this->levels;
    }

    public function setLevels(DimensionTypeLevels $levels)
    {
        $this->levels = $levels;
        return $this;
    }

    public function getAddress(): DimensionTypeAddress
    {
        return $this->address;
    }

    public function setAddress(DimensionTypeAddress $address)
    {
        $this->address = $address;
        return $this;
    }
}
