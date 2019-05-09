<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\Level1234\DimensionType\MaskField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionType extends BaseObject
{
    use CodeField;
    use MaskField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;

    private $address;
    private $levels;

    public function __construct()
    {
        $this->setAddress(new DimensionTypeAddress);
        $this->setLevels(new DimensionTypeLevels);
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

    public function getLevels(): DimensionTypeLevels
    {
        return $this->levels;
    }

    public function setLevels(DimensionTypeLevels $levels)
    {
        $this->levels = $levels;
        return $this;
    }
}