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
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
        // Generate new Article object
        $currency = new Currency();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Article elements and their methods
        $currencyTags = [
            'code'                       => 'setCode',
            'office'                     => 'setOfficeByCode',
            'name'                       => 'setName',
            'shortname'                  => 'setShortName'
        ];

        foreach ($currencyTags as $tagName => $tagValue) {
            $value = $responseDOM->getElementsByTagName($tagName)->item(0)->nodeValue;
            $method = $currencyTags[$tagName];
            $currency->$method($value);
        }

        $ratesDOMTag = $responseDOM->getElementsByTagName('rates');

        if (isset($ratesDOMTag) && $ratesDOMTag->length > 0) {
            // Element tags and their methods for lines
            $lineTags = [
                'status'    => 'setStatus',
                'startdate' => 'startdate',
                'rate'      => 'setRate'
            ];

            $ratesDOM = $ratesDOMTag->item(0);

            // Loop through each returned line for the article
            foreach ($ratesDOM->getElementsByTagName('rate') as $rateDom) {
                if( $rateDom->childElementCount > 0 ) {
                    // Make a new tempory CurrencyRate class
                    $rateLine = new CurrencyRate();

                    // Set the attributes ( id,status,inuse)
                    $rateLine->setStartdate($rateDom->getElementsByTagName('startdate')->item(0)->nodeValue)
                        ->setRate($rateDom->getElementsByTagName('rate')->item(0)->nodeValue);

                    // Add the bank to the customer
                    $currency->addRate($rateLine);

                    // Clean that memory!
                    unset ($rateLine);
                }
            }
        }
        return $currency;
    }
}
