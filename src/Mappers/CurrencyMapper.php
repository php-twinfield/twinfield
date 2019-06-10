<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Currency;
use PhpTwinfield\CurrencyRate;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CurrencyMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Currency entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Currency
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Currency object
        $currency = new Currency();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/currency element
        $currencyElement = $responseDOM->documentElement;

         // Set the result and status attribute
        $currency->setResult($currencyElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $currencyElement->getAttribute('status')));

        // Set the currency elements from the currency element
        $currency->setCode(self::getField($currencyElement, 'code', $currency))
            ->setName(self::getField($currencyElement, 'name', $currency))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $currency, $currencyElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($currencyElement, 'shortname', $currency));

        // Get the rates element
        $ratesDOMTag = $responseDOM->getElementsByTagName('rates');

        if (isset($ratesDOMTag) && $ratesDOMTag->length > 0) {
            // Loop through each returned rate for the currency
            foreach ($ratesDOMTag->item(0)->childNodes as $rateElement) {
                if ($rateElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary CurrencyRate class
                $currencyRate = new CurrencyRate();

                // Set the status
                // NOTE: Contrary to what is written in the API documentation status is not implemented for currency rates, DO NOT SET!
                //$currencyRate->setStatus($rateElement->getAttribute('status'));

                // Set the currency rate elements from the rate element
                $currencyRate->setRate(self::getField($rateElement, 'rate', $currencyRate))
                    ->setStartDate(self::parseDateAttribute(self::getField($rateElement, 'startdate', $currencyRate)));

                // Add the rate to the currency
                $currency->addRate($currencyRate);

                // Clean that memory!
                unset ($currencyRate);
            }
        }

        // Return the complete object
        return $currency;
    }
}
