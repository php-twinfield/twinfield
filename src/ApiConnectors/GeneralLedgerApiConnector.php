<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\GeneralLedgersDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\GeneralLedger;
use PhpTwinfield\Mappers\GeneralLedgerMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * GeneralLedgers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class GeneralLedgerApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific GeneralLedger based off the passed in code, dimension type and optionally the office.
     *
     * @param string $code
     * @param string $dimType
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return GeneralLedger The requested GeneralLedger or GeneralLedger object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, string $dimType, Office $office): GeneralLedger
    {
        // Make a request to read a single GeneralLedger. Set the required values
        $request_generalLedger = new Request\Read\GeneralLedger();
        $request_generalLedger
            ->setOffice($office)
            ->setDimType($dimType)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_generalLedger);

        return GeneralLedgerMapper::map($response);
    }

    /**
     * Sends a GeneralLedger instance to Twinfield to update or add.
     *
     * @param GeneralLedger $generalLedger
     * @return GeneralLedger
     * @throws Exception
     */
    public function send(GeneralLedger $generalLedger): GeneralLedger
    {
		foreach($this->sendAll([$generalLedger]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param GeneralLedger[] $generalLedgers
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $generalLedgers): MappedResponseCollection
    {
        Assert::allIsInstanceOf($generalLedgers, GeneralLedger::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($generalLedgers) as $chunk) {
            $generalLedgersDocument = new GeneralLedgersDocument();

            foreach ($chunk as $generalLedger) {
                $generalLedgersDocument->addGeneralLedger($generalLedger);
            }

            $responses[] = $this->sendXmlDocument($generalLedgersDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): GeneralLedger {
            return GeneralLedgerMapper::map($response);
        });
    }

    /**
     * List all fixed assets.
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
     * @return GeneralLedger[] The fixed assets found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $forcedOptions['level'] = 1;
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options, $forcedOptions);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $generalLedgerListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(GeneralLedger::class, $response->data, $generalLedgerListAllTags);
    }

    /**
     * Deletes a specific GeneralLedger based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param string $dimType
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return GeneralLedger The deleted GeneralLedger or GeneralLedger object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, string $dimType, Office $office): GeneralLedger
    {
        $generalLedger = self::get($code, $dimType, $office);

        if ($generalLedger->getResult() == 1) {
            $generalLedger->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $generalLedgerDeleted = self::send($generalLedger);
            } catch (ResponseException $e) {
                $generalLedgerDeleted = $e->getReturnedObject();
            }

            return $generalLedgerDeleted;
        } else {
            return $generalLedger;
        }
    }
}
