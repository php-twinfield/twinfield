<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\CurrencyField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\Rate\TypeField;
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
    use CreatedField;
    use CurrencyField;
    use ModifiedField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use TypeField;
    use UnitField;
    use UserField;

    private $rateChanges = [];

    public function getRateChanges()
    {
        return $this->rateChanges;
    }

    public function addRateChange(RateRateChange $rateChange)
    {
        $this->rateChanges[] = $rateChange;
        return $this;
    }

    public function removeRateChange($index)
    {
        if (array_key_exists($index, $this->rateChanges)) {
            unset($this->rateChanges[$index]);
            return true;
        } else {
            return false;
        }
    }
}
