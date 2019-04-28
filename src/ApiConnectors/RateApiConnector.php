<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\RatesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\RateMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Rate;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Rates.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleApiConnector by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class RateApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Rate based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Rate|bool The requested rate or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Rate
    {
        // Make a request to read a single Rate. Set the required values
        $request_rate = new Request\Read\Rate();
        $request_rate
            ->setOffice($office->getCode())
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
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $rates): MappedResponseCollection
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

        return $this->getProcessXmlService()->mapAll($responses, "projectrate", function(Response $response): Rate {
            return RateMapper::map($response);
        });
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
        $optionsArrayOfString = array('ArrayOfString' => array());

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_TIME_PROJECT_RATES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $rates = [];
        foreach ($response->data->Items->ArrayOfString as $rateArray) {
            $rate = new Rate();

            if (isset($rateArray->string[0])) {
                $rate->setCode($rateArray->string[0]);
                $rate->setName($rateArray->string[1]);
            } else {
                $rate->setCode($rateArray[0]);
                $rate->setName($rateArray[1]);
            }

            $rates[] = $rate;
        }

        return $rates;
    }
}
