<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Mappers\OfficeMapper;
use PhpTwinfield\Exception;
use PhpTwinfield\Office;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Request\Catalog\Office as OfficeRequestDocument;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * offices.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Emile Bons <emile@emilebons.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class OfficeApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Office based off the passed in code.
     *
     * @param string $code
     * @return Office|bool The requested office or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code): Office
    {
        // Make a request to read a single Office. Set the required values
        $request_office = new Request\Read\Office();
        $request_office->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_office);

        return OfficeMapper::map($response);
    }

    /**
     * List the available offices when you are using the OAuth based authentication and don't have an office code yet.
     * For more information following see.
     *
     * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Types/XmlWebServices
     * @throws \SoapFault
     * @throws \PhpTwinfield\Exception
     * @throws \ErrorException
     * @return Office[] The offices found.
     */
    public function listAllWithoutOfficeCode(): array
    {
        $offices = [];
        $document = new OfficeRequestDocument();
        $response = $this->getProcessXmlService()->sendDocument($document);
        $response->assertSuccessful();

        foreach (OfficeMapper::mapAll($response) as $office) {
            $offices[] = $office;
        }

        return $offices;
    }

    /**
     * List all offices.
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
     * @return Office[] The offices found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_OFFICES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $officeListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
            2       => 'setCountryCode',
            3       => 'setVatPeriod',
            4       => 'setVatFirstQuarterStartsIn',
        );

        return $this->mapListAll("Office", $response->data, $officeListAllTags);
    }
}
