<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups
 * @todo Add documentation and typehints to all properties.
 */
class DimensionGroup extends BaseObject
{
    use CodeField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;

    private $dimensions = [];

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
