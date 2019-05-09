<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;
use PhpTwinfield\Fields\UserField;
use PhpTwinfield\Fields\VatCode\VatCodeTypeField;

/**
 * Class VatCode
 *
 * @author Emile Bons <emile@emilebons.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatCode extends BaseObject
{
    use CodeField;
    use CreatedField;
    use ModifiedField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UIDField;
    use UserField;
    use VatCodeTypeField;

    private $percentages = [];

    public function getPercentages()
    {
        return $this->percentages;
    }

    public function addPercentage(VatCodePercentage $percentage)
    {
        $this->percentages[] = $percentage;
        return $this;
    }

    public function removePercentage($index)
    {
        if (array_key_exists($index, $this->percentages)) {
            unset($this->percentages[$index]);
            return true;
        } else {
            return false;
        }
    }
}