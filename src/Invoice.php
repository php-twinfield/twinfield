<?php
namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;

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
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices
 * @todo Add documentation and typehints to all properties.
 */
class Invoice extends BaseObject
{
    use PeriodField;
    use OfficeField;

    private $customer;
    private $customerName;
    private $debitCredit;
    private $invoiceAmount;
    private $invoiceType;
    private $invoiceNumber;
    private $status;
    private $currency;
    private $invoiceDate;
    private $dueDate;
    private $performanceDate;
    private $paymentMethod;
    private $bank;
    private $invoiceAddressNumber;
    private $deliverAddressNumber;
    private $headerText;
    private $footerText;
    private $totals;

    private $financialCode;
    private $financialNumber;

    /**
     * @var InvoiceLine[]
     */
    private $lines = [];

    public function addLine(InvoiceLine $line)
    {
        $this->lines[$line->getID()] = $line;
        return $this;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

	/**
     * @var InvoiceVatLine[]
     */
    private $vatlines = [];

    public function addVatLine(InvoiceVatLine $vatline)
    {
        $this->vatlines[] = $vatline;
        return $this;
    }

    /**
     * @return InvoiceVatLine[]
     */
    public function getVatLines(): array
    {
        return $this->vatlines;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomerName()
    {
        return $this->customerName;
    }

    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function getDebitCredit()
    {
        return $this->debitCredit;
    }

    public function setDebitCredit($debitCredit)
    {
        $this->debitCredit = $debitCredit;
        return $this;
    }

    public function setTotals(InvoiceTotals $totals)
    {
        $this->totals = $totals;
        return $this;
    }

    public function getTotals(): InvoiceTotals
    {
        return $this->totals;
    }

    public function getInvoiceAmount()
    {
        return $this->invoiceAmount;
    }

    public function setInvoiceAmount($invoiceAmount)
    {
        $this->invoiceAmount = $invoiceAmount;
        return $this;
    }

    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    public function setInvoiceType($invoiceType)
    {
        $this->invoiceType = $invoiceType;
        return $this;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function getPerformanceDate()
    {
        return $this->performanceDate;
    }

    public function setPerformanceDate($performanceDate)
    {
        $this->performanceDate = $performanceDate;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    public function getInvoiceAddressNumber()
    {
        return $this->invoiceAddressNumber;
    }

    public function setInvoiceAddressNumber($invoiceAddressNumber)
    {
        $this->invoiceAddressNumber = $invoiceAddressNumber;
        return $this;
    }

    public function getDeliverAddressNumber()
    {
        return $this->deliverAddressNumber;
    }

    public function setDeliverAddressNumber($delivererAddressNumber)
    {
        $this->deliverAddressNumber = $delivererAddressNumber;
        return $this;
    }

    public function getHeaderText()
    {
        return $this->headerText;
    }

    public function setHeaderText($headerText)
    {
        $this->headerText = $headerText;
        return $this;
    }

    public function getFooterText()
    {
        return $this->footerText;
    }

    public function setFooterText($footerText)
    {
        $this->footerText = $footerText;
        return $this;
    }

    public function getFinancialCode()
    {
        return $this->financialCode;
    }

    public function setFinancialCode($financialCode)
    {
        $this->financialCode = $financialCode;
	    return $this;
    }

    public function getFinancialNumber()
    {
        return $this->financialNumber;
    }

    public function setFinancialNumber($financialNumber)
    {
        $this->financialNumber = $financialNumber;
	    return $this;
    }

    public function getMatchReference(): MatchReferenceInterface
    {
        return new MatchReference($this->getOffice(), $this->getFinancialCode(), $this->getFinancialNumber(), 1);
    }
}
