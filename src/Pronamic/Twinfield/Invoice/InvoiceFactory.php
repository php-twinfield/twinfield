<?php
namespace Pronamic\Twinfield\Invoice;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper;
use \Pronamic\Twinfield\Request as Request;

/**
 * InvoiceFactory
 * 
 * A facade factory to make interaction with the twinfield service easier
 * when trying to retrieve or set information about Invoices.
 * 
 * Each method has detailed explanation over what is required, and what
 * happens.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see the
 * advanced guide details the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Invoice
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class InvoiceFactory extends ParentFactory
{
    /**
     * Requires a specific invoice based off the passed in
     * code, invoiceNumber and office.
     * 
     * Office is an optional parameter.
     * 
     * First it attempts to login with the passed configuration into
     * this instances constructor. If successful it will get the Service
     * class to handle further interactions.
     * 
     * If no office has been passed it will instead take the default office
     * from the passed in Config class.
     * 
     * It makes a new instance of the Request\Read\Invoice() and sets the
     * office, number and code parameters.
     * 
     * Use the Service class it will attempt to send the DOM document from
     * Read\Invoice();
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will return a
     * \Pronamic\Twinfield\Invoice\Invoice instance, made by the
     * \Pronamic\Twinfield\Invoice\Mapper\InvoiceMapper class.
     * 
     * @access public
     * @param string $code
     * @param int $invoiceNumber
     * @param string $office
     * @return type
     */
    public function get($code, $invoiceNumber, $office = null)
    {
        // Attempts to process the login.
        if ($this->getLogin()->process()) {

            // Gets the secure service class
            $service = $this->getService();

            // No office passed, use the one from Config
            if (!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single invoice. Set the required values
            $request_invoice = new Request\Read\Invoice();
            $request_invoice
                ->setCode($code)
                ->setNumber($invoiceNumber)
                ->setOffice($office);

            // Send the Request document and set the response to this instance
            $response = $service->send($request_invoice);
            $this->setResponse($response);

            // Return a mapped invoice if successful or false if not.
            if ($response->isSuccessful()) {
                return InvoiceMapper::map($response);
            } else {
                return false;
            }
        }
    }

    /**
     * Sends a \Pronamic\Twinfield\Invoice\Invoice instance to Twinfield
     * to update or add.
     * 
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     * 
     * It will then make an instance of 
     * \Pronamic\Twinfield\Invoice\DOM\InvoicesDocument where it will
     * pass in the Invoice class in this methods parameter.
     * 
     * It will then attempt to send the DOM document from InvoicesDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     * 
     * If successful will return true else will return false.
     * 
     * If you want to map the response back into an invoice use getResponse()->
     * getResponseDocument()->asXML() into the InvoiceMapper::map method.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Invoice\Invoice $invoice
     * @return boolean
     */
    public function send(Invoice $invoice)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            
            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of InvoicesDocument and sets the invoice
            $invoicesDocument = new DOM\InvoicesDocument();
            $invoicesDocument->addInvoice($invoice);

            // Sends the DOM document request and sets the response
            $response = $service->send($invoicesDocument);
            $this->setResponse($response);

            // Return a bool on status of response
            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        }
    }
}
