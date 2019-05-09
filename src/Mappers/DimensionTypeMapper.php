<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\DimensionType;
use PhpTwinfield\DimensionTypeAddress;
use PhpTwinfield\DimensionTypeLevels;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionTypeMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean DimensionType entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return DimensionType
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new DimensionType object
        $dimensiontype = new DimensionType();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/dimensiontype element
        $dimensiontypeElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $dimensiontype->setResult($dimensiontypeElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $dimensiontypeElement->getAttribute('status')));

        // Set the dimension type elements from the dimension type element
        $dimensiontype->setCode(self::getField($dimensiontype, $dimensiontypeElement, 'code'))
            ->setMask(self::getField($dimensiontype, $dimensiontypeElement, 'mask'))
            ->setName(self::getField($dimensiontype, $dimensiontypeElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $dimensiontype, $dimensiontypeElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($dimensiontype, $dimensiontypeElement, 'shortname'));

        // Get the levels element
        $levelsElement = $responseDOM->getElementsByTagName('levels')->item(0);

        if ($levelsElement !== null) {
            // Make a new temporary DimensionTypeLevels class
            $dimensionTypeLevels = new DimensionTypeLevels();

            // Set the dimension type levels elements from the levels element
            $dimensionTypeLevels->setFinancials(self::getField($dimensionTypeLevels, $levelsElement, 'financials'))
                ->setTime(self::getField($dimensionTypeLevels, $levelsElement, 'time'));

            // Set the custom class to the dimension type
            $dimensiontype->setLevels($dimensionTypeLevels);
        }

        // Get the address element
        $addressElement = $responseDOM->getElementsByTagName('address')->item(0);

        if ($addressElement !== null) {
            // Make a new temporary DimensionTypeAddress class
            $dimensionTypeAddress = new DimensionTypeAddress();

            // Set the dimension type address elements from the address element
            $dimensionTypeAddress->setLabel1(self::getField($dimensionTypeAddress, $addressElement, 'label1'))
                ->setLabel2(self::getField($dimensionTypeAddress, $addressElement, 'label2'))
                ->setLabel3(self::getField($dimensionTypeAddress, $addressElement, 'label3'))
                ->setLabel4(self::getField($dimensionTypeAddress, $addressElement, 'label4'))
                ->setLabel5(self::getField($dimensionTypeAddress, $addressElement, 'label5'))
                ->setLabel6(self::getField($dimensionTypeAddress, $addressElement, 'label6'));

            // Set the custom class to the dimensiontype
            $dimensiontype->setAddress($dimensionTypeAddress);
        }

        // Return the complete object
        return $dimensiontype;
    }
}