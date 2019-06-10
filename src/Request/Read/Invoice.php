<?php
namespace PhpTwinfield\Request\Read;

use PhpTwinfield\Office;

/**
 * Used to request a specific Invoice from a certain
 * code, invoice number and office.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Invoice extends Read
{
     /**
     * Sets the <type> to salesinvoice for the request
     * and sets the office, code and invoice number if they
     * are present.
     *
     * @access public
     * @param Office|null $office
     * @param string $code
     * @param int $invoiceNumber
     */
    public function __construct(?Office $office = null, $code = null, $invoiceNumber = null)
    {
        parent::__construct();

        $this->add('type', 'salesinvoice');

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }

        if (null !== $invoiceNumber) {
            $this->setInvoiceNumber($invoiceNumber);
        }
    }

    /**
     * Sets the invoice number for this request.
     *
     * @access public
     * @param string $invoiceNumber
     * @return \PhpTwinfield\Request\Read\Invoice
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->add('invoicenumber', $invoiceNumber);
        return $this;
    }
}
