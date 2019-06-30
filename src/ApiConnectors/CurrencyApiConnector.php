<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Currency;
use PhpTwinfield\DomDocuments\CurrenciesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\HasMessageInterface;
use PhpTwinfield\Mappers\CurrencyMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Util;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Currencies.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CurrencyApiConnector extends BaseApiConnector implements HasEqualInterface
{
    /**
     * Requests a specific Currency based off the passed in code and optionally the office.
     * NOTE: The Twinfield API does not currently officially support reading currencies
     * This function uses the fact that the API will return part of an existing object when sending a known code with an explicit error (no name)
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Currency      The requested Currency or Currency object with error message if it can't be found.
     * @throws Exception
     */

    public function get(string $code, Office $office): Currency
    {
        $currency = new Currency;
        $currency->setCode($code);
        $currency->setOffice($office);
        $currencyName = '';

        $currencies = self::listAll($code, 1, 1, 100, array('office' => $office->getCode()));

        if (count($currencies) == 0) {
            $currency->setResult(0);
            return $currency;
        }

        foreach ($currencies as $currencyListing) {
            if ($currencyListing->getCode() == $code) {
                $currencyName = $currencyListing->getName();
                break;
            }
        }

        if (empty($currencyName)) {
            $currency->setResult(0);
            return $currency;
        }

        try {
            $currencyResponse = $this->send($currency);
        } catch (ResponseException $e) {
            $currencyResponse = $e->getReturnedObject();
            $currencyResponse->setMessages(null);
            $currencyResponse->setName($currencyName);
            $currencyResponse->setResult(1);
        }

        return $currencyResponse;
    }

    /**
     * Sends a Currency instance to Twinfield to update or add.
     *
     * @param Currency $currency
     * @return Currency
     * @throws Exception
     */
    public function send(Currency $currency): Currency
    {
        foreach($this->sendAll([$currency]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param Currency[] $currencies
     * @param bool|null $reSend
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $currencies, bool $reSend = false): MappedResponseCollection
    {
        Assert::allIsInstanceOf($currencies, Currency::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($currencies) as $chunk) {

            $currenciesDocument = new CurrenciesDocument();

            foreach ($chunk as $currency) {
                $currenciesDocument->addCurrency($currency);
            }

            $responses[] = $this->sendXmlDocument($currenciesDocument);
        }

        $mappedResponseCollection = $this->getProcessXmlService()->mapAll($responses, "currency", function(Response $response): Currency {
            return CurrencyMapper::map($response);
        });

        if ($reSend) {
            return $mappedResponseCollection;
        }

        return self::testSentEqualsResponse($this, $currencies, $mappedResponseCollection);
    }

    /**
     * @param HasMessageInterface $returnedObject
     * @param HasMessageInterface $sentObject
     * @return array
     */
    public function testEqual(HasMessageInterface $returnedObject, HasMessageInterface $sentObject): array
    {
        Assert::IsInstanceOf($returnedObject, Currency::class);
        Assert::IsInstanceOf($sentObject, Currency::class);

        $currencyResponse = $this->get($returnedObject->getCode(), $returnedObject->getOffice());

        foreach ($returnedObject->getRates() as $key => $rate) {
            $returnedObject->removeRate($key);
        }

        foreach ($currencyResponse->getRates() as $key => $rate) {
            $returnedObject->addRate($rate);
        }

        $equal = true;

        $dateArray = [];

        $returnedRates = $returnedObject->getRates();
        $sentRates = $sentObject->getRates();

        foreach ($sentRates as $sentRate) {
            $dateArray[] = Util::formatDate($sentRate->getStartDate());
        }

        foreach ($returnedRates as $returnedRate) {
            $date = Util::formatDate($returnedRate->getStartDate());

            if (!in_array($date, $dateArray)) {
                $returnedRate->setStatus(\PhpTwinfield\Enums\Status::DELETED());
                $equal = false;
            }
        }

        return [$equal, $returnedObject];
    }

    /**
     * List all currencies.
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
     * @return Currency[] The currencies found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_CURRENCIES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $currencyArrayListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(Currency::class, $response->data, $currencyArrayListAllTags);
    }

    /**
     * Deletes a specific Currency based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Currency      The deleted Currency or Currency object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): Currency
    {
        $currency = self::get($code, $office);

        if ($currency->getResult() == 1) {
            $currency->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $currencyDeleted = self::send($currency);
            } catch (ResponseException $e) {
                $currencyDeleted = $e->getReturnedObject();
            }

            return $currencyDeleted;
        } else {
            return $currency;
        }
    }
}
