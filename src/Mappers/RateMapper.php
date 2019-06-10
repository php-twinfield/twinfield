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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
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

        // Get the root/rate element
        $rateElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $rate->setResult($rateElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $rateElement->getAttribute('status')));

        // Set the rate elements from the rate element
        $rate->setCode(self::getField($rateElement, 'code', $rate))
            ->setCreated(self::parseDateTimeAttribute(self::getField($rateElement, 'created', $rate)))
            ->setCurrency(self::parseObjectAttribute('Currency', $rate, $rateElement, 'currency', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setModified(self::parseDateTimeAttribute(self::getField($rateElement, 'modified', $rate)))
            ->setName(self::getField($rateElement, 'name', $rate))
            ->setOffice(self::parseObjectAttribute('Office', $rate, $rateElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($rateElement, 'shortname', $rate))
            ->setTouched(self::getField($rateElement, 'touched', $rate))
            ->setType(self::parseEnumAttribute('RateType', self::getField($rateElement, 'type', $rate)))
            ->setUnit(self::getField($rateElement, 'unit', $rate))
            ->setUser(self::parseObjectAttribute('User', $rate, $rateElement, 'user', array('name' => 'setName', 'shortname' => 'setShortName')));

        // Get the ratechanges element
        $ratechangesDOMTag = $responseDOM->getElementsByTagName('ratechanges');

        if (isset($ratechangesDOMTag) && $ratechangesDOMTag->length > 0) {
            // Loop through each returned ratechange for the rate
            foreach ($ratechangesDOMTag->item(0)->childNodes as $ratechangeElement) {
                if ($ratechangeElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary RateRateChange class
                $rateRateChange = new RateRateChange();

                $rateRateChange->setID($ratechangeElement->getAttribute('id'))
                    ->setStatus(self::parseEnumAttribute('Status', $rateElement->getAttribute('status')));

                // Set the project rate rate change elements from the ratechange element
                $rateRateChange->setBeginDate(self::parseDateAttribute(self::getField($ratechangeElement, 'begindate', $rateRateChange)))
                    ->setEndDate(self::parseDateAttribute(self::getField($ratechangeElement, 'enddate', $rateRateChange)))
                    ->setExternalRate(self::getField($ratechangeElement, 'externalrate', $rateRateChange))
                    ->setInternalRate(self::getField($ratechangeElement, 'internalrate', $rateRateChange));

                // Add the rate change to the project rate
                $rate->addRateChange($rateRateChange);

                // Clean that memory!
                unset ($rateRateChange);
            }
        }

        // Return the complete object
        return $rate;
    }
}