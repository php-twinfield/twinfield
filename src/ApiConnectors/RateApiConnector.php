<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\RatesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\HasMessageInterface;
use PhpTwinfield\Mappers\RateMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Rate;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\IndividualMappedResponse;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Rates.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class RateApiConnector extends BaseApiConnector implements HasEqualInterface
{
    /**
     * Requests a specific Rate based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Rate          The requested Rate or Rate object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Rate
    {
        // Make a request to read a single Rate. Set the required values
        $request_rate = new Request\Read\Rate();
        $request_rate
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_rate);

        return RateMapper::map($response);
    }

    /**
     * Sends a Rate instance to Twinfield to update or add.
     *
     * @param Rate $rate
     * @return Rate
     * @throws Exception
     */
    public function send(Rate $rate): Rate
    {
		foreach($this->sendAll([$rate]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param Rate[] $rates
     * @param bool|null $reSend
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $rates, bool $reSend = false): MappedResponseCollection
    {
        Assert::allIsInstanceOf($rates, Rate::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($rates) as $chunk) {

            $ratesDocument = new RatesDocument();

            foreach ($chunk as $rate) {
                $ratesDocument->addRate($rate);
            }

            $responses[] = $this->sendXmlDocument($ratesDocument);
        }

        $mappedResponseCollection = $this->getProcessXmlService()->mapAll($responses, "projectrate", function(Response $response): Rate {
            return RateMapper::map($response);
        });

        if ($reSend) {
            return $mappedResponseCollection;
        }

        return self::testSentEqualsResponse($this, $rates, $mappedResponseCollection);
    }

    /**
     * @param HasMessageInterface $returnedObject
     * @param HasMessageInterface $sentObject
     * @return array
     */
    public function testEqual(HasMessageInterface $returnedObject, HasMessageInterface $sentObject): array
    {
        Assert::IsInstanceOf($returnedObject, Rate::class);
        Assert::IsInstanceOf($sentObject, Rate::class);

        $equal = true;
        $idArray = [];

        $returnedRateChanges = $returnedObject->getRateChanges();
        $sentRateChanges = $sentObject->getRateChanges();

        foreach ($sentRateChanges as $key => $sentRateChange) {
            $idArray[] = $sentRateChange->getID();
        }

        foreach ($returnedRateChanges as $key => $returnedRateChange) {
            $id = $returnedRateChange->getID();

            if (!in_array($id, $idArray)) {
                $returnedRateChange->setStatus(\PhpTwinfield\Enums\Status::DELETED());
                $equal = false;
            }
        }

        return [$equal, $returnedObject];
    }

    /**
     * List all rates.
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
     * @return Rate[] The rates found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_TIME_PROJECT_RATES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $rateListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(Rate::class, $response->data, $rateListAllTags);
    }

    /**
     * Deletes a specific Rate based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Rate          The deleted Rate or Rate object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): Rate
    {
        $rate = self::get($code, $office);

        if ($rate->getResult() == 1) {
            $rate->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $rateDeleted = self::send($rate);
            } catch (ResponseException $e) {
                $rateDeleted = $e->getReturnedObject();
            }

            return $rateDeleted;
        } else {
            return $rate;
        }
    }
}