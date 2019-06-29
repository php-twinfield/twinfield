<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\CostCenter;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CostCenterMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean CostCenter entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return CostCenter
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new CostCenter object
        $costCenter = new CostCenter();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/costcenter element
        $costCenterElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $costCenter->setResult($costCenterElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $costCenterElement->getAttribute('status')));

        // Set the cost center elements from the cost center element
        $costCenter->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($costCenterElement, 'behaviour', $costCenter)))
            ->setCode(self::getField($costCenterElement, 'code', $costCenter))
            ->setInUse(Util::parseBoolean(self::getField($costCenterElement, 'name', $costCenter)))
            ->setName(self::getField($costCenterElement, 'name', $costCenter))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $costCenter, $costCenterElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($costCenterElement, 'touched', $costCenter))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $costCenter, $costCenterElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($costCenterElement, 'uid', $costCenter));

        // Return the complete object
        return $costCenter;
    }
}
