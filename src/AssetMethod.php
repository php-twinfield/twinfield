<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\AssetMethod\CalcMethodField;
use PhpTwinfield\Fields\AssetMethod\DepreciateReconciliationField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\NrOfPeriodsField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\PercentageField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UserField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethod extends BaseObject implements HasCodeInterface
{
    use CalcMethodField;
    use CodeField;
    use CreatedField;
    use DepreciateReconciliationField;
    use InUseField;
    use ModifiedField;
    use NameField;
    use NrOfPeriodsField;
    use OfficeField;
    use PercentageField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UserField;

    private $balanceAccounts;
    private $profitLossAccounts;

    private $freeTexts = [];

    public function __construct()
    {
        $this->setBalanceAccounts(new AssetMethodBalanceAccounts);
        $this->setProfitLossAccounts(new AssetMethodProfitLossAccounts);
    }

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }

    public function getBalanceAccounts(): AssetMethodBalanceAccounts
    {
        return $this->balanceAccounts;
    }

    public function setBalanceAccounts(AssetMethodBalanceAccounts $balanceAccounts)
    {
        $this->balanceAccounts = $balanceAccounts;
        return $this;
    }

    public function getProfitLossAccounts(): AssetMethodProfitLossAccounts
    {
        return $this->profitLossAccounts;
    }

    public function setProfitLossAccounts(AssetMethodProfitLossAccounts $profitLossAccounts)
    {
        $this->profitLossAccounts = $profitLossAccounts;
        return $this;
    }

    public function getFreeTexts()
    {
        return $this->freeTexts;
    }

    public function addFreeText(AssetMethodFreeText $freeText)
    {
        $this->freeTexts[] = $freeText;
        return $this;
    }

    public function removeFreeText($index)
    {
        if (array_key_exists($index, $this->freeTexts)) {
            unset($this->freeTexts[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function removeFreeTextByID($id)
    {
        $found = false;

        foreach ($this->freeTexts as $index => $freeText) {
            if ($id == $freeText->getID()) {
                unset($this->freeTexts[$index]);
                $found = true;
            }
        }

        if ($found) {
            return true;
        }

        return false;
    }
}
