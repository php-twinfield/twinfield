<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Exception;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseSortField;
use PhpTwinfield\Mappers\BrowseDataMapper;
use PhpTwinfield\Mappers\BrowseDefinitionMapper;
use PhpTwinfield\Request\BrowseData;
use PhpTwinfield\Mappers\BrowseFieldMapper;
use PhpTwinfield\Request\Catalog\BrowseField;
use PhpTwinfield\Request\Read\BrowseDefinition;
use Webmozart\Assert\Assert;

class BrowseDataApiConnector extends BaseApiConnector
{
    /**
     * Requests the browse definition of the given browse code.
     *
     * See the Twinfield documentation for the available browse codes:
     * https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData#Determine-which-browse-definition-to-use
     *
     * @param string $code
     * @return \PhpTwinfield\BrowseDefinition
     * @throws Exception
     */
    public function getBrowseDefinition(string $code)
    {
        // Make a request to read the browse definition of the given browse code.
        $requestBrowseDefinition = new BrowseDefinition($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($requestBrowseDefinition);
        return BrowseDefinitionMapper::map($response);
    }

    /**
     * Requests the definition of the browse fields. Browse definitions exists of browse fields.
     * Each browse fields has its own definition and can be determined by using this function.
     *
     * Note that which browse fields can be used for getting browse data depends on the browse code which is used.
     *
     * @return \PhpTwinfield\BrowseField[]
     * @throws Exception
     */
    public function getBrowseFields()
    {
        // Make a request to retrieve the list of browse fields.
        $requestBrowseField = new BrowseField();

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($requestBrowseField);
        return BrowseFieldMapper::map($response);
    }

    /**
     * Requests financial data from Twinfield. This function is based on so called browse codes. These codes are
     * predefined definitions of financial data. For more information see the Twinfield documentation:
     * https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData
     *
     * @param string $code
     * @param BrowseColumn[] $columns
     * @param BrowseSortField[] $sortFields
     * @return \PhpTwinfield\BrowseData
     * @throws Exception
     */
    public function getBrowseData(string $code, array $columns, array $sortFields = [])
    {
        Assert::minCount($columns, 1);
        Assert::allIsInstanceOf($columns, BrowseColumn::class);
        Assert::allIsInstanceOf($sortFields, BrowseSortField::class);

        $requestBrowseData = new BrowseData($code, $columns, $sortFields);

        $response = $this->sendXmlDocument($requestBrowseData);
        return BrowseDataMapper::map($response);
    }
}
