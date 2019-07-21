<?php
namespace PhpTwinfield;

use PhpTwinfield\Fields\Invoice\ValueExclField;
use PhpTwinfield\Fields\Invoice\ValueIncField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class InvoiceTotals extends BaseObject
{
    use ValueExclField;
    use ValueIncField;
}
