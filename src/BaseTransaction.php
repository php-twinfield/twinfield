<?php

namespace PhpTwinfield;

use PhpTwinfield\BookingReference;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CurrencyField;
use PhpTwinfield\Fields\DateField;
use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\PeriodField;
use PhpTwinfield\Fields\Transaction\AutoBalanceVatField;
use PhpTwinfield\Fields\Transaction\DateRaiseWarningField;
use PhpTwinfield\Fields\Transaction\DestinyField;
use PhpTwinfield\Fields\Transaction\InputDateField;
use PhpTwinfield\Fields\Transaction\LinesField;
use PhpTwinfield\Fields\Transaction\ModificationDateField;
use PhpTwinfield\Fields\Transaction\NumberField;
use PhpTwinfield\Fields\Transaction\OriginField;
use PhpTwinfield\Fields\Transaction\RaiseWarningField;
use PhpTwinfield\Fields\UserField;
use PhpTwinfield\MatchReferenceInterface;

abstract class BaseTransaction extends BaseObject
{
    use AutoBalanceVatField;
    use CodeField;
    use CurrencyField;
    use DateField;
    use DateRaiseWarningField;
    use DestinyField;
    use FreeText1Field;
    use FreeText2Field;
    use FreeText3Field;
    use InputDateField;
    use LinesField;
    use ModificationDateField;
    use NumberField;
    use OfficeField;
    use OriginField;
    use PeriodField;
    use RaiseWarningField;

    public function getBookingReference(): BookingReference
    {
        return new BookingReference(
            $this->office,
            $this->code,
            $this->number
        );
    }
}