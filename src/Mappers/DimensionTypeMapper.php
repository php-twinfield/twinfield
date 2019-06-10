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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $dimensiontypeElement->getAttribute('status')));

        // Set the dimension type elements from the dimension type element
        $dimensiontype->setCode(self::getField($dimensiontypeElement, 'code', $dimensiontype))
            ->setMask(self::getField($dimensiontypeElement, 'mask', $dimensiontype))
            ->setName(self::getField($dimensiontypeElement, 'name', $dimensiontype))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $dimensiontype, $dimensiontypeElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($dimensiontypeElement, 'shortname', $dimensiontype));

        // Get the levels element
        $levelsElement = $responseDOM->getElementsByTagName('levels')->item(0);

        if ($levelsElement !== null) {
            // Make a new temporary DimensionTypeLevels class
            $dimensionTypeLevels = new DimensionTypeLevels();

            // Set the dimension type levels elements from the levels element
            $dimensionTypeLevels->setFinancials(self::getField($levelsElement, 'financials', $dimensionTypeLevels))
                ->setTime(self::getField($levelsElement, 'time', $dimensionTypeLevels));

            // Set the custom class to the dimension type
            $dimensiontype->setLevels($dimensionTypeLevels);
        }

        // Get the address element
        $addressElement = $responseDOM->getElementsByTagName('address')->item(0);

        if ($addressElement !== null) {
            // Make a new temporary DimensionTypeAddress class
            $dimensionTypeAddress = new DimensionTypeAddress();

            // Set the dimension type address elements from the address element
            $dimensionTypeAddress->setLabel1(self::getField($addressElement, 'label1', $dimensionTypeAddress))
                ->setLabel2(self::getField($addressElement, 'label2', $dimensionTypeAddress))
                ->setLabel3(self::getField($addressElement, 'label3', $dimensionTypeAddress))
                ->setLabel4(self::getField($addressElement, 'label4', $dimensionTypeAddress))
                ->setLabel5(self::getField($addressElement, 'label5', $dimensionTypeAddress))
                ->setLabel6(self::getField($addressElement, 'label6', $dimensionTypeAddress));

            // Set the custom class to the dimensiontype
            $dimensiontype->setAddress($dimensionTypeAddress);
        }

        // Return the complete object
        return $dimensiontype;
    }
}