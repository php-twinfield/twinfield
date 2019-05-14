<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\VatGroupCountry;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatGroupCountryMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean VatGroupCountry entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return VatGroupCountry
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new VatGroupCountry object
        $vatGroupCountry = new VatGroupCountry();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/vat group country element
        $vatGroupElement = $responseDOM->documentElement;

        // Set the vat group country elements from the vat group country element
        $vatGroupCountry->setCode(self::getField($vatGroupCountry, $vatGroupElement, 'code'))
            ->setName(self::getField($vatGroupCountry, $vatGroupElement, 'name'))
            ->setShortName(self::getField($vatGroupCountry, $vatGroupElement, 'shortname'));

        // Return the complete object
        return $vatGroupCountry;
    }
}