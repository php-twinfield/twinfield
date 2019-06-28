<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\DateField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\PercentageField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\UserField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodePercentage extends BaseObject
{
    use CreatedField;
    use DateField;
    use InUseField;
    use PercentageField;
    use NameField;
    use ShortNameField;
    use StatusField;
    use UserField;

    private $accounts = [];

    public function getAccounts()
    {
        return $this->accounts;
    }

    public function addAccount(VatCodeAccount $account)
    {
        $this->accounts[] = $account;
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

    public function removeAccountByID($id)
    {
        $found = false;

        foreach ($this->accounts as $index => $account) {
            if ($id == $account->getID()) {
                unset($this->accounts[$index]);
                $found = true;
            }
        }

        if ($found) {
            return true;
        }

        return false;
    }
}