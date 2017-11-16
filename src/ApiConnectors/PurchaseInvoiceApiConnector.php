<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\PurchaseInvoice\Mapper\PurchaseInvoiceMapper;
use PhpTwinfield\PurchaseInvoice\PurchaseInvoice;

/**
 * A facade to make interaction with the Twinfield service easier when trying to retrieve or set information about
 * Purchase Invoices.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide details the required usages.
 *
 * @author Emile Bons <emile@emilebons.nl>
 */
class PurchaseInvoiceApiConnector extends BaseApiConnector
{
    /**
     * The WSDL location of the ProcessXML service.
     *
     * @const string
     */
    private const WSDL = '/webservices/processxml.asmx?wsdl';

    /**
     * Processes the XML-formatted request and returns the response.
     * @param $xmlRequest
     * @return mixed
     */
    private function processXmlString(string $xmlRequest): \stdClass
    {
        $client = $this->getClient(self::WSDL);

        return $client->ProcessXmlString(['xmlRequest' => $xmlRequest]);
    }

    private function readTransactionData(
        string $officeCode,
        string $transactionCode,
        string $transactionNumber
    ): \stdClass {
        $xml = new \SimpleXMLElement('<read/>');

        $xml->addChild('office', $officeCode);
        $xml->addChild('code', $transactionCode);
        $xml->addChild('number', $transactionNumber);
        $xml->addChild('type', 'transaction');

        return $this->processXmlString($xml->asXML());
    }
    /**
     * Returns a specific purchase invoice by the given code and invoice number.
     *
     * If the response was successful it will return a PurchaseInvoice instance, made by the PurchaseInvoiceMapper
     * class.
     *
     * @param string $invoiceCode
     * @param string $invoiceNumber
     * @param string $officeCode
     *
     * @return PurchaseInvoice
     */
    public function get(string $invoiceCode, string $invoiceNumber, string $officeCode): PurchaseInvoice
    {
        if ($this->getLogin()->process()) {
            $response = $this->readTransactionData($officeCode, $invoiceCode, $invoiceNumber);

            return PurchaseInvoiceMapper::map($response->ProcessXmlStringResult);
        }
    }
}
