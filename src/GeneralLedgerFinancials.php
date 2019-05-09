<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\AccountTypeField;
use PhpTwinfield\Fields\Level1234\LevelField;
use PhpTwinfield\Fields\Level1234\MatchTypeField;
use PhpTwinfield\Fields\Level1234\SubAnalyseField;
use PhpTwinfield\Fields\Level1234\VatCodeFixedField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets
 * @todo Add documentation and typehints to all properties.
 */
class GeneralLedgerFinancials extends BaseObject
{
    use AccountTypeField;
    use LevelField;
    use MatchTypeField;
    use SubAnalyseField;
    use VatCodeField;
    use VatCodeFixedField;

    private $childValidations = [];

    public function __construct()
    {
        $this->setMatchTypeFromString('notmatchable');
        $this->setSubAnalyseFromString('maybe');
    }

    public function getChildValidations()
    {
        return $this->childValidations;
    }

    public function addChildValidation(GeneralLedgerChildValidation $childValidation)
    {
        $this->childValidations[] = $childValidation;
        return $this;
    }

    public function removeChildValidation($index)
    {
        if (array_key_exists($index, $this->childValidations)) {
            unset($this->childValidations[$index]);
            return true;
        } else {
            return false;
        }
    }
}
