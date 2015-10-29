<?php

namespace Pronamic\Twinfield\Factory;

/**
 * Several entities use the ProcessXML web service for read, create, update and delete actions. The instructions are
 * provided as an XML-formatted string to the ProcessXML web service which performs the action(s) (bulk-actions are
 * supported as well) and returns the result(s), again as an XML-formatted string. The functions in this factory make
 * the ProcessXML web service accessible.
 *
 * The 'browse data' functionality of the Twinfield API is used to browse financial data. The browse data service uses
 * browse codes. These are pre-defined in constants prefixed with 'BROWSE_CODE'.
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData
 *
 * @note Always include the session id SOAP header when calling web services.
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Types/XmlWebServices
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield
 */
class ProcessXmlFactory extends ParentFactory
{
    const BROWSE_CODE_GENERAL_LEDGER_TRANSACTIONS = '000';
    const BROWSE_CODE_TRANSACTIONS_STILL_TO_BE_MATCHED = '010';
    const BROWSE_CODE_TRANSACTION_LIST = '020';
    const BROWSE_CODE_CUSTOMER_TRANSACTIONS = '100';
    const BROWSE_CODE_DOCKET_INVOICES = '163';
    const BROWSE_CODE_SUPPLIER_TRANSACTIONS = '200';
    const BROWSE_CODE_PROJECT_TRANSACTIONS = '300';
    const BROWSE_CODE_ASSET_TRANSACTIONS = '301';
    const BROWSE_CODE_CASH_TRANSACTIONS = '400';
    const BROWSE_CODE_BANK_TRANSACTIONS = '410';
    const BROWSE_CODE_COST_CENTERS = '900';
    const BROWSE_CODE_GENERAL_LEDGER_DETAILS_V1 = '030_1';
    const BROWSE_CODE_GENERAL_LEDGER_DETAILS_V2 = '030_2';
    const BROWSE_CODE_GENERAL_LEDGER_INTER_COMPANY = '031';
    const BROWSE_CODE_ANNUAL_REPORT_TOTALS = '040_1';
    const BROWSE_CODE_ANNUAL_REPORT_YTD = '050_1';
    const BROWSE_CODE_ANNUAL_REPORT_TOTALS_MULTI_CURRENCY = '060';
    const BROWSE_CODE_CUSTOMERS = '130_1';
    const BROWSE_CODE_CREDIT_MANAGEMENT = '164';
    const BROWSE_CODE_SUPPLIERS = '230_1';
    const BROWSE_CODE_FIXED_ASSETS = '302_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_TOTALS = '610_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_MULTI_CURRENCY = '620';
    const BROWSE_CODE_TIME_AND_EXPENSES_DETAILS = '650_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_TOTALS_PER_WEEK = '651_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_TOTALS_PER_PERIOD = '652_2';
    const BROWSE_CODE_TIME_AND_EXPENSES_BILLING_DETAILS = '660_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_BILLING_PER_WEEK = '661_1';
    const BROWSE_CODE_TIME_AND_EXPENSES_BILLING_PER_PERIOD = '662_1';
    const BROWSE_CODE_TRANSACTION_SUMMARY = '670';
    const BROWSE_CODE_BANK_LINK_DETAILS = '680';
    const BROWSE_CODE_VAT_RETURN_STATUS = '690';
    const BROWSE_CODE_HIERARCHY_ACCESS = '700';

    /**
     * @var string the WSDL location of the finder service
     */
    private $wsdl = '/webservices/processxml.asmx?wsdl';

    /**
     * @param string $office the code of the office for which the data should be searched
     * @param string $code the browse code, see constants
     * @return string the browse data, XML-formatted
     */
    public function browseData($office, $code)
    {
        $xml = new \SimpleXMLElement('<read/>');
        $xml->addChild('type', 'browse');
        $xml->addChild('office', $office);
        $xml->addChild('code', $code);
        return $this->processXmlString($xml->asXML());
    }

    /**
     * @return \SoapClient the process XML web service client
     */
    private function getProcessXmlClient()
    {
        return $this->getClient($this->wsdl);
    }

    /**
     * Processes the XML-formatted request and returns the response
     * @param $xmlRequest
     * @return mixed
     */
    public function processXmlString($xmlRequest)
    {
        return $this->getProcessXmlClient()->ProcessXmlString(['xmlRequest' => $xmlRequest]);
    }

    /**
     * Read transaction data
     * @param string $office the office for which the data should be searched
     * @param string $code the code
     * @param string $number the number for which data should be searched
     * @return string the transaction data, XML-formatted
     */
    public function readTransactionData($office, $code, $number)
    {
        $xml = new \SimpleXMLElement('<read/>');
        $xml->addChild('office', $office);
        $xml->addChild('code', $code);
        $xml->addChild('number', $number);
        $xml->addChild('type', 'transaction');
        $response = $this->processXmlString($xml->asXML());
        return $response;
    }
}