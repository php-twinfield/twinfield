<?php
namespace PhpTwinfield;

use PhpTwinfield\Fields\Invoice\PerformanceDateField;
use PhpTwinfield\Fields\Invoice\PerformanceTypeField;
use PhpTwinfield\Fields\Invoice\VatValueField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceVatLine extends BaseObject
{
    use PerformanceDateField;
    use PerformanceTypeField;
    use VatValueField;
    use VatCodeField;
}
