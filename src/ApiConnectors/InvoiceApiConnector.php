<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\InvoicesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Invoice;
use PhpTwinfield\Mappers\InvoiceMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
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
     * Requires a specific Invoice based off the passed in code, invoiceNumber and optionally the office.
     *
     * @param string $code
     * @param string $invoiceNumber
     * @param Office $office
     * @return Invoice
     * @throws Exception
     */
    public function get(string $code, string $invoiceNumber, Office $office)
    {
        // Make a request to read a single Invoice. Set the required values
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
         foreach($this->sendAll([$invoice]) as $each) {
            return $each->unwrap();
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

    /**
     * List all sales invoices.
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    The search field determines which field or fields will be searched. The available fields
     *                         depends on the finder type. Passing a value outside the specified values will cause an
     *                         error.
     * @param int    $firstRow First row to return, useful for paging
     * @param int    $maxRows  Maximum number of rows to return, useful for paging
     * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     *
     * @return Invoice[] The sales invoices found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = array('ArrayOfString' => array());

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_LIST_OF_AVAILABLE_INVOICES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $invoices = [];

        foreach ($response->data->Items->ArrayOfString as $invoiceArray) {
            $invoice = new Invoice();
            $customer = new Customer();

            if (isset($invoiceArray->string[0])) {
                $invoice->setInvoiceNumber($invoiceArray->string[0]);
                $invoice->setInvoiceAmount($invoiceArray->string[1]);
                $customer->setCode($invoiceArray->string[2]);
                $invoice->setCustomerName($invoiceArray->string[3]);
                $invoice->setDebitCredit($invoiceArray->string[4]);
            } else {
                $invoice->setInvoiceNumber($invoiceArray[0]);
                $invoice->setInvoiceAmount($invoiceArray[1]);
                $customer->setCode($invoiceArray[2]);
                $invoice->setCustomerName($invoiceArray[3]);
                $invoice->setDebitCredit($invoiceArray[4]);
            }

            $invoice->setCustomer($customer);

            $invoices[] = $invoice;
        }

        return $invoices;
    }
}
