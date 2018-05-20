<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Invoice;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

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
     * @param string $code
     * @param string $invoiceNumber
     * @param Office $office
     * @return Invoice
     * @throws Exception
     */
    public function get(string $code, string $invoiceNumber, Office $office)
    {
        // Make a request to read a single invoice. Set the required values
        $request_invoice = new Request\Read\Invoice();
        $request_invoice
            ->setCode($code)
            ->setNumber($invoiceNumber)
            ->setOffice($office->getCode());

        // Send the Request document and set the response to this instance
        $response = $this->sendXmlDocument($request_invoice);

        return InvoiceMapper::map($response);
    }

    /**
     * Sends a \PhpTwinfield\Invoice\Invoice instance to Twinfield to update or add.
     *
     * @param Invoice $invoice
     * @return Invoice
     * @throws Exception
     */
    public function send(Invoice $invoice): Invoice
    {
        $invoiceResponses = $this->sendAll([$invoice]);

        Assert::count($invoiceResponses, 1);

        foreach ($invoiceResponses as $invoiceResponse) {
            return $invoiceResponse->unwrap();
        }
    }

    /**
     * @param Invoice[] $invoices
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $invoices): MappedResponseCollection
    {
        Assert::allIsInstanceOf($invoices, Invoice::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($invoices) as $chunk) {

            $invoicesDocument = new InvoicesDocument();

            foreach ($chunk as $invoice) {
                $invoicesDocument->addInvoice($invoice);
            }

            $responses[] = $this->sendXmlDocument($invoicesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "salesinvoice", function(Response $response): Invoice {
            return InvoiceMapper::map($response);
        });
    }
}
