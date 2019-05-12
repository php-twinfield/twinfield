<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CurrencyField;
use PhpTwinfield\Fields\DescriptionField;
use PhpTwinfield\Fields\Dimensions\AmountField;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerPostingRule extends BaseObject
{
    use AmountField;
    use CurrencyField;
    use DescriptionField;
    use IDField;
    use StatusField;

    private $lines = [];

    public function getLines()
    {
        return $this->lines;
    }

    public function addLine(CustomerLine $line)
    {
        $this->lines[] = $line;
        return $this;
    }

    public function removeLine($index)
    {
        if (array_key_exists($index, $this->lines)) {
            unset($this->lines[$index]);
            return true;
        } else {
            return false;
        }
    }
}
