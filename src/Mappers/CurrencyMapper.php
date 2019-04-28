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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
        $currencyElement = $responseDOM->documentElement;

         // Set the result and status attribute
        $currency->setResult($currencyElement->getAttribute('result'));
        $currency->setStatus($currencyElement->getAttribute('status'));

        // Currency elements and their methods
        $currencyTags = [
            'code'                       => 'setCode',
            'name'                       => 'setName',
            'office'                     => 'setOffice',
            'shortname'                  => 'setShortName',
        ];

        // Loop through all the tags
        foreach ($currencyTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$currency, $method]);
        }

        $ratesDOMTag = $responseDOM->getElementsByTagName('rates');

        if (isset($ratesDOMTag) && $ratesDOMTag->length > 0) {
            // Element tags and their methods for rates
            $rateTags = [
                'rate'          => 'setRate',
                'startdate'     => 'setStartDate',
            ];

            $ratesDOM = $ratesDOMTag->item(0);

            // Loop through each returned rate for the currency
            foreach ($ratesDOM->childNodes as $rateDOM) {

                // Make a new tempory CurrencyRate class
                $currencyRate = new CurrencyRate();

                // Set the status
                $currencyRate->setStatus($rateDOM->getAttribute('status'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($rateTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $rateDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $currencyRate->$method($_tag->textContent);
                    }
                }

                // Add the rate to the currency
                $currency->addRate($currencyRate);

                // Clean that memory!
                unset ($currencyRate);
            }
        }
        return $currency;
    }
}
