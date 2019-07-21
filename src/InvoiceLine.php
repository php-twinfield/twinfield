<?php
namespace PhpTwinfield;

use PhpTwinfield\Fields\DescriptionField;
use PhpTwinfield\Fields\Dim1Field;
use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\Invoice\AllowDiscountOrPremiumField;
use PhpTwinfield\Fields\Invoice\ArticleField;
use PhpTwinfield\Fields\Invoice\QuantityField;
use PhpTwinfield\Fields\Invoice\SubArticleField;
use PhpTwinfield\Fields\Invoice\UnitsField;
use PhpTwinfield\Fields\Invoice\UnitsPriceExclField;
use PhpTwinfield\Fields\Invoice\UnitsPriceIncField;
use PhpTwinfield\Fields\Invoice\ValueExclField;
use PhpTwinfield\Fields\Invoice\ValueIncField;
use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\VatCodeField;
use PhpTwinfield\Fields\VatValueField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceLine extends BaseObject
{
    use AllowDiscountOrPremiumField;
    use ArticleField;
    use DescriptionField;
    use Dim1Field;
    use FreeText1Field;
    use FreeText2Field;
    use FreeText3Field;
    use IDField;
    use PerformanceDateField;
    use PerformanceTypeField;
    use QuantityField;
    use SubArticleField;
    use UnitsField;
    use UnitsPriceExclField;
    use UnitsPriceIncField;
    use ValueExclField;
    use ValueIncField;
    use VatCodeField;
    use VatValueField;

    public function __construct($quantity = null, $article = null, $freeText1 = null, $freeText2 = null)
    {
        $this->quantity  = $quantity;
        $this->article   = $article;
        $this->setFreeText1($freeText1);
        $this->setFreeText2($freeText2);
        $this->setAllowDiscountOrPremium(true);
    }
}
