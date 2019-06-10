<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\DimensionGroup;
use PhpTwinfield\DimensionGroupDimension;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionGroupMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean DimensionGroup entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return DimensionGroup
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new DimensionGroup object
        $dimensiongroup = new DimensionGroup();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/dimensiongroup element
        $dimensiongroupElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $dimensiongroup->setResult($dimensiongroupElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $dimensiongroupElement->getAttribute('status')));

        // Set the dimension group elements from the dimension group element
        $dimensiongroup->setCode(self::getField($dimensiongroupElement, 'code', $dimensiongroup))
            ->setName(self::getField($dimensiongroupElement, 'name', $dimensiongroup))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $dimensiongroup, $dimensiongroupElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($dimensiongroupElement, 'shortname', $dimensiongroup));

        // Get the dimensions element
        $dimensionsDOMTag = $responseDOM->getElementsByTagName('dimensions');

        if (isset($dimensionsDOMTag) && $dimensionsDOMTag->length > 0) {
            // Loop through each returned dimension for the dimensiongroup
            foreach ($dimensionsDOMTag->item(0)->childNodes as $dimensionElement) {
                if ($dimensionElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary DimensionGroupDimension class
                $dimensionGroupDimension = new DimensionGroupDimension();

                // Set the dimension group dimension elements from the dimension element
                $dimensionGroupDimension->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $dimensionGroupDimension, $dimensionElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')));
                $dimensionGroupDimension->setCode(self::parseObjectAttribute('DimensionGroupDimension', $dimensionGroupDimension, $dimensionElement, 'code', array('name' => 'setName', 'shortname' => 'setShortName')));

                // Add the dimension to the dimension group
                $dimensiongroup->addDimension($dimensionGroupDimension);

                // Clean that memory!
                unset ($dimensionGroupDimension);
            }
        }

        // Return the complete object
        return $dimensiongroup;
    }
}
