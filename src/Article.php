<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\Invoice\AllowDiscountOrPremiumField;
use PhpTwinfield\Fields\Invoice\Article\AllowChangePerformanceTypeField;
use PhpTwinfield\Fields\Invoice\Article\AllowChangeUnitsPriceField;
use PhpTwinfield\Fields\Invoice\Article\AllowChangeVatCodeField;
use PhpTwinfield\Fields\Invoice\Article\AllowDecimalQuantityField;
use PhpTwinfield\Fields\Invoice\Article\PercentageField;
use PhpTwinfield\Fields\Invoice\Article\TypeField;
use PhpTwinfield\Fields\Invoice\Article\UnitNamePluralField;
use PhpTwinfield\Fields\Invoice\Article\UnitNameSingularField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles
 * @todo Add documentation and typehints to all properties.
 */
class Article extends BaseObject implements HasCodeInterface
{
    use AllowChangePerformanceTypeField;
    use AllowChangeUnitsPriceField;
    use AllowChangeVatCodeField;
    use AllowDecimalQuantityField;
    use AllowDiscountOrPremiumField;
    use CodeField;
    use NameField;
    use OfficeField;
    use PercentageField;
    use PerformanceTypeField;
    use ShortNameField;
    use StatusField;
    use TypeField;
    use UnitNamePluralField;
    use UnitNameSingularField;
    use VatCodeField;

    private $lines = [];

    public function __construct()
    {
        $this->setAllowChangePerformanceType(true);
        $this->setAllowChangeUnitsPrice(false);
        $this->setAllowChangeVatCode(false);
        $this->setAllowDecimalQuantity(false);
        $this->setAllowDiscountorPremium(true);
        $this->setPercentage(false);
        $this->setType(\PhpTwinfield\Enums\ArticleType::NORMAL());
    }

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function addLine(ArticleLine $line)
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

    public function removeLineByID($id)
    {
        $found = false;

        foreach ($this->lines as $index => $line) {
            if ($id == $line->getID()) {
                unset($this->lines[$index]);
                $found = true;
            }
        }

        if ($found) {
            return true;
        }

        return false;
    }
}
