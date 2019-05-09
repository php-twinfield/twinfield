<?php
namespace PhpTwinfield;

use PhpTwinfield\Fields\CurrencyField;
use PhpTwinfield\Fields\CustomerField;
use PhpTwinfield\Fields\Invoice\BankField;
use PhpTwinfield\Fields\Invoice\CalculateOnlyField;
use PhpTwinfield\Fields\Invoice\CustomerNameField;
use PhpTwinfield\Fields\Invoice\DeliverAddressNumberField;
use PhpTwinfield\Fields\Invoice\DueDateField;
use PhpTwinfield\Fields\Invoice\FinancialCodeField;
use PhpTwinfield\Fields\Invoice\FinancialNumberField;
use PhpTwinfield\Fields\Invoice\FooterTextField;
use PhpTwinfield\Fields\Invoice\HeaderTextField;
use PhpTwinfield\Fields\Invoice\InvoiceAddressNumberField;
use PhpTwinfield\Fields\Invoice\InvoiceAmountField;
use PhpTwinfield\Fields\Invoice\InvoiceDateField;
use PhpTwinfield\Fields\Invoice\InvoiceDebitCreditField;
use PhpTwinfield\Fields\Invoice\InvoiceNumberField;
use PhpTwinfield\Fields\Invoice\InvoiceTypeField;
use PhpTwinfield\Fields\Invoice\InvoiceStatusField;
use PhpTwinfield\Fields\Invoice\PaymentMethodField;
use PhpTwinfield\Fields\Invoice\PerformanceDateField;
use PhpTwinfield\Fields\Invoice\RaiseWarningField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\PeriodField;

/**
 * Invoice Class
 *
 * Is an object for mapping data from a response ( or making
 * a request ).
 *
 * It is normally passed into the Element class to convert it
 * into the XML format.
 *
 * @since 0.0.1
 *
 * @package PhpTwinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class Invoice extends BaseObject
{
    use BankField;
    use CalculateOnlyField;
    use CurrencyField;
    use CustomerField;
    use CustomerNameField;
    use DeliverAddressNumberField;
    use DueDateField;
    use FinancialCodeField;
    use FinancialNumberField;
    use FooterTextField;
    use HeaderTextField;
    use InvoiceAddressNumberField;
    use InvoiceAmountField;
    use InvoiceDateField;
    use InvoiceDebitCreditField;
    use InvoiceNumberField;
    use InvoiceTypeField;
    use InvoiceStatusField;
    use OfficeField;
    use PaymentMethodField;
    use PerformanceDateField;
    use PeriodField;
    use RaiseWarningField;

    private $totals;
    private $lines = [];
    private $vatlines = [];

    public function setTotals(InvoiceTotals $totals)
    {
        $this->totals = $totals;
        return $this;
    }

    public function getTotals(): InvoiceTotals
    {
        return $this->totals;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function addLine(InvoiceLine $line)
    {
        $this->lines[$line->getID()] = $line;
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

    /**
     * @return InvoiceVatLine[]
     */
    public function getVatLines(): array
    {
        return $this->vatlines;
    }

    public function addVatLine(InvoiceVatLine $vatline)
    {
        $this->vatlines[] = $vatline;
        return $this;
    }

    public function getMatchReference(): MatchReferenceInterface
    {
        return new MatchReference($this->getOffice(), $this->getFinancialCode(), $this->getFinancialNumber(), 1);
    }
}