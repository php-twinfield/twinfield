<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\VatGroup;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatGroupMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean VatGroup entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return VatGroup
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new VatGroup object
        $vatGroup = new VatGroup();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/vat group element
        $vatGroupElement = $responseDOM->documentElement;

        // Set the vat group elements from the vat group element
        $vatGroup->setCode(self::getField($vatGroupElement, 'code', $vatGroup))
            ->setName(self::getField($vatGroupElement, 'name', $vatGroup))
            ->setShortName(self::getField($vatGroupElement, 'shortname', $vatGroup));

        // Return the complete object
        return $vatGroup;
    }
}