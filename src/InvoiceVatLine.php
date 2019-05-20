<?php
namespace PhpTwinfield;

use PhpTwinfield\Fields\PerformanceDateField;
use PhpTwinfield\Fields\PerformanceTypeField;
use PhpTwinfield\Fields\VatCodeField;
use PhpTwinfield\Fields\VatValueField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceVatLine extends BaseObject
{
    use PerformanceDateField;
    use PerformanceTypeField;
    use VatCodeField;
    use VatValueField;
}
