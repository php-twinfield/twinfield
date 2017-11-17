<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Invoice;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Request as Request;

/**
 * A facade to make interaction with the Twinfield service easier when trying to retrieve or set information about
 * Invoices.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide details the required usages.
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class InvoiceApiConnector extends BaseApiConnector
{
    /**
     * Requires a specific invoice based off the passed in code, invoiceNumber and optionally the office.
     *
     * @param string      $code
     * @param string      $invoiceNumber
     * @param string|null $office        Optional. If no office has been passed it will instead take the default office
     *                                   from the passed in Config class.
     * @return Invoice|bool The requested invoice or false if it can't be found.
     */
    public function get(string $code, string $invoiceNumber, ?string $office = null)
    {
        // Attempts to process the login.
        if ($this->getLogin()->process()) {
            // Gets the secure service class
            $service = $this->createService();

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
            }
        }

        return false;
    }

    /**
     * Sends a \PhpTwinfield\Invoice\Invoice instance to Twinfield to update or add.
     *
     * If you want to map the response back into an invoice use getResponse()->getResponseDocument()->asXML() into the
     * InvoiceMapper::map() method.
     *
     * @param Invoice $invoice
     * @return bool
     */
    public function send(Invoice $invoice): bool
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            // Gets a new instance of InvoicesDocument and sets the invoice
            $invoicesDocument = new InvoicesDocument();
            $invoicesDocument->addInvoice($invoice);

            // Sends the DOM document request and sets the response
            $response = $service->send($invoicesDocument);
            $this->setResponse($response);

            // Return a bool on status of response
            return $response->isSuccessful();
        }

        return false;
    }
}
