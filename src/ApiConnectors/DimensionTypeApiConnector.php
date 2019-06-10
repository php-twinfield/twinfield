<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DimensionType;
use PhpTwinfield\DomDocuments\DimensionTypesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\DimensionTypeMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * DimensionTypes.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionTypeApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific DimensionType based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return DimensionType The requested DimensionType or DimensionType object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): DimensionType
    {
        // Make a request to read a single DimensionType. Set the required values
        $request_dimensionType = new Request\DimensionType();
        $request_dimensionType
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_dimensionType);

        return DimensionTypeMapper::map($response);
    }

    /**
     * Sends an DimensionType instance to Twinfield to update or add.
     *
     * @param DimensionType $dimensionType
     * @return DimensionType
     * @throws Exception
     */
    public function send(DimensionType $dimensionType): DimensionType
    {
		foreach($this->sendAll([$dimensionType]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param DimensionType[] $dimensionTypes
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $dimensionTypes): MappedResponseCollection
    {
        Assert::allIsInstanceOf($dimensionTypes, DimensionType::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($dimensionTypes) as $chunk) {

            $dimensionTypesDocument = new DimensionTypesDocument();

            foreach ($chunk as $dimensionType) {
                $dimensionTypesDocument->addDimensionType($dimensionType);
            }

            $responses[] = $this->sendXmlDocument($dimensionTypesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimensiontype", function(Response $response): DimensionType {
            return DimensionTypeMapper::map($response);
        });
    }

	/**
     * List all dimension types.
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
     * @return DimensionType[] The dimension types found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSION_TYPES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $dimensionTypeListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(\PhpTwinfield\DimensionType::class, $response->data, $dimensionTypeListAllTags);
    }
}