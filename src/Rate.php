<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CurrencyField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\Rate\RateTypeField;
use PhpTwinfield\Fields\Rate\UnitField;
use PhpTwinfield\Fields\UserField;

/**
 * Class Rate
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Rate extends BaseObject
{
    use CodeField;
    use CurrencyField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use RateTypeField;
    use UnitField;
    use UserField;

    private $ratechanges = [];

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
