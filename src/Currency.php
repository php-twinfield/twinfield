<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
 * @todo Add documentation and typehints to all properties.
 */
class Currency extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;

    private $rates = [];

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
