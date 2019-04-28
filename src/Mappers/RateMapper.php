<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Rate;
use PhpTwinfield\RateRateChange;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class RateMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean Rate entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Rate
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Rate object
        $rate = new Rate();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $rateElement = $responseDOM->documentElement;

        // Set the status and result attribute
        $rate->setStatus($rateElement->getAttribute('status'));
        $rate->setResult($rateElement->getAttribute('result'));

        // Rate elements and their methods
        $rateTags = array(
            'code'              => 'setCode',
            'currency'          => 'setCurrency',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'shortname'         => 'setShortName',
            'type'              => 'setType',
            'unit'              => 'setUnit',
        );

        // Loop through all the tags
        foreach ($rateTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$rate, $method]);
        }

        $ratechangesDOMTag = $responseDOM->getElementsByTagName('ratechanges');

        if (isset($ratechangesDOMTag) && $ratechangesDOMTag->length > 0) {
            // Element tags and their methods for ratechanges
            $ratechangeTags = [
                'begindate'         => 'setBeginDate',
                'enddate'           => 'setEndDate',
                'externalrate'      => 'setExternalRate',
                'internalrate'      => 'setInternalRate',
            ];

            $ratechangesDOM = $ratechangesDOMTag->item(0);

            // Loop through each returned ratechange for the rate
            foreach ($ratechangesDOM->childNodes as $ratechangeDOM) {

                // Make a new tempory RateRateChange class
                $rateRateChange = new RateRateChange();

                $rateRateChange->setID($ratechangeDOM->getAttribute('id'))
                    ->setLastUsed($ratechangeDOM->getAttribute('lastused'))
                    ->setStatus($ratechangeDOM->getAttribute('status'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($ratechangeTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $ratechangeDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $rateRateChange->$method($_tag->textContent);
                    }
                }

                // Add the ratechange to the project rate
                $rate->addRateChange($rateRateChange);

                // Clean that memory!
                unset ($rateRateChange);
            }
        }
        return $rate;
    }
}
