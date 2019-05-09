<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\PercentageField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\UserField;
use PhpTwinfield\Fields\VatCode\DateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodePercentage extends BaseObject
{
    use CreatedField;
    use DateField;
    use PercentageField;
    use NameField;
    use ShortNameField;
    use UserField;

    private $accounts = [];

    public function getAccounts()
    {
        return $this->accounts;
    }

    public function addAccount(VatCodeAccount $account)
    {
        $this->accounts[$account->getID()] = $account;
        return $this;
    }

    public function removeAccount($index)
    {
        if (array_key_exists($index, $this->accounts)) {
            unset($this->accounts[$index]);
            return true;
        } else {
            return false;
        }
    }
}
