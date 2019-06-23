<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DimensionGroup;
use PhpTwinfield\DomDocuments\DimensionGroupsDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\DimensionGroupMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * DimensionGroups.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionGroupApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific DimensionGroup based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office   If no office has been passed it will instead take the default office from the
     *                         passed in config class.
     * @return DimensionGroup  The requested DimensionGroup or DimensionGroup object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): DimensionGroup
    {
        // Make a request to read a single DimensionGroup. Set the required values
        $request_dimensionGroup = new Request\DimensionGroup();
        $request_dimensionGroup
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_dimensionGroup);

        return DimensionGroupMapper::map($response);
    }

    /**
     * Sends an DimensionGroup instance to Twinfield to update or add.
     *
     * @param DimensionGroup $dimensionGroup
     * @return DimensionGroup
     * @throws Exception
     */
    public function send(DimensionGroup $dimensionGroup): DimensionGroup
    {
		foreach($this->sendAll([$dimensionGroup]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param DimensionGroup[] $dimensionGroups
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $dimensionGroups): MappedResponseCollection
    {
        Assert::allIsInstanceOf($dimensionGroups, DimensionGroup::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($dimensionGroups) as $chunk) {

            $dimensionGroupsDocument = new DimensionGroupsDocument();

            foreach ($chunk as $dimensionGroup) {
                $dimensionGroupsDocument->addDimensionGroup($dimensionGroup);
            }

            $responses[] = $this->sendXmlDocument($dimensionGroupsDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimensiongroup", function(Response $response): DimensionGroup {
            return DimensionGroupMapper::map($response);
        });
    }

	/**
     * List all dimension groups.
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
     * @return DimensionGroup[] The dimension groups found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSION_GROUPS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $dimensionGroupListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(DimensionGroup::class, $response->data, $dimensionGroupListAllTags);
    }

    /**
     * Deletes a specific DimensionGroup based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office   If no office has been passed it will instead take the default office from the
     *                         passed in config class.
     * @return DimensionGroup  The deleted DimensionGroup or DimensionGroup object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): DimensionGroup
    {
        $dimensionGroup = self::get($code, $office);

        if ($dimensionGroup->getResult() == 1) {
            $dimensionGroup->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $dimensionGroupDeleted = self::send($dimensionGroup);
            } catch (ResponseException $e) {
                $dimensionGroupDeleted = $e->getReturnedObject();
            }

            return $dimensionGroupDeleted;
        } else {
            return $dimensionGroup;
        }
    }
}