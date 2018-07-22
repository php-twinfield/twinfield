<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\Office;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 */
class OfficeMapper extends BaseMapper
{
    /**
     * Maps multiple offices to an office array.
     *
     * @param Response $response
     * @return \Generator
     */
    public static function mapAll(Response $response): \Generator
    {
        foreach ($response->getResponseDocument()->getElementsByTagName("office") as $officeElement) {
            yield self::mapElement($officeElement);
        }
    }

    /**
     * Maps a DOMElement to our Office class.
     *
     * @param \DOMElement $officeElement
     * @return Office
     */
    public static function mapElement(\DOMElement $officeElement): Office
    {
        $office = new Office();
        $office->setName($officeElement->attributes->getNamedItem("name")->textContent);
        $office->setCode($officeElement->nodeValue);

        return $office;
    }
}