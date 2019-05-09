<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\CostCenter;
use PhpTwinfield\DomDocuments\CostCentersDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\CostCenterMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * CostCenters.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CostCenterApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific CostCenter based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return CostCenter|bool The requested cost center or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): CostCenter
    {
        // Make a request to read a single CostCenter. Set the required values
        $request_costCenter = new Request\Read\CostCenter();
        $request_costCenter
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_costCenter);

        return CostCenterMapper::map($response);
    }

    /**
     * Sends a CostCenter instance to Twinfield to update or add.
     *
     * @param CostCenter $costCenter
     * @return CostCenter
     * @throws Exception
     */
    public function send(CostCenter $costCenter): CostCenter
    {
		foreach($this->sendAll([$costCenter]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param CostCenter[] $costCenters
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $costCenters): MappedResponseCollection
    {
        Assert::allIsInstanceOf($costCenters, CostCenter::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($costCenters) as $chunk) {

            $costCentersDocument = new CostCentersDocument();

            foreach ($chunk as $costCenter) {
                $costCentersDocument->addCostCenter($costCenter);
            }

            $responses[] = $this->sendXmlDocument($costCentersDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): CostCenter {
            return CostCenterMapper::map($response);
        });
    }

    /**
     * List all cost centers.
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
     * @return CostCenter[] The cost centers found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $forcedOptions['dimtype'] = "KPL";
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options, $forcedOptions);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $costCenterArrayListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll("CostCenter", $response->data, $costCenterArrayListAllTags);
    }
}