<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

use Pronamic\Twinfield\Factory\ProcessXmlFactory;
use Pronamic\Twinfield\PurchaseInvoice\Mapper\PurchaseInvoiceMapper;

/**
 * InvoiceFactory
 *
 * A facade factory to make interaction with the twinfield service easier
 * when trying to retrieve or set information about Purchase Invoices.
 *
 * Each method has detailed explanation over what is required, and what
 * happens.
 *
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see the
 * advanced guide details the required usages.
 *
 * @package Pronamic\Twinfield
 * @subpackage PurchaseInvoice
 * @author Emile Bons <emile@emilebons.nl>
 */
class PurchaseInvoiceFactory extends ProcessXmlFactory
{
    /**
     * Returns a specific purchase invoice by the code, invoice
     * number given.
     *
     * If the response was successful it will return a
     * \Pronamic\Twinfield\PurchaseInvoice\PurchaseInvoice instance, made by the
     * \Pronamic\Twinfield\PurchaseInvoice\Mapper\PurchaseInvoiceMapper class.
     *
     * @access public
     * @param string $code
     * @param int $invoiceNumber
     * @param string $office
     * @return PurchaseInvoice
     */
    public function get($code, $invoiceNumber, $office)
    {
        if ($this->getLogin()->process()) {
            $response = $this->readTransactionData($office, $code, $invoiceNumber);
            return PurchaseInvoiceMapper::map($response->ProcessXmlStringResult);
        }
    }
}