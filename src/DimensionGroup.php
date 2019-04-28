<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups
 * @todo Add documentation and typehints to all properties.
 */
class DimensionGroup extends BaseObject
{
    use OfficeField;

    private $code;
    private $name;
    private $shortName;
    private $status;
    private $dimensions = [];

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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

    public function getDimensions()
    {
        return $this->dimensions;
    }

    public function addDimension(DimensionGroupDimension $dimension)
    {
        $this->dimensions[] = $dimension;
        return $this;
    }

    public function removeDimension($index)
    {
        if (array_key_exists($index, $this->dimensions)) {
            unset($this->dimensions[$index]);
            return true;
        } else {
            return false;
        }
    }
}
