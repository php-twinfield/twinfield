<?php

namespace PhpTwinfield\Mappers;

use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 */
class OfficeMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Office entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Office
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Office object
        $office = new Office();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/office element
        $officeElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $office->setResult($officeElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $officeElement->getAttribute('status')));

        // Set the office elements from the office element
        $office->setCode(self::getField($officeElement, 'code', $office))
            ->setCreated(self::parseDateTimeAttribute(self::getField($officeElement, 'created', $office)))
            ->setModified(self::parseDateTimeAttribute(self::getField($officeElement, 'modified', $office)))
            ->setName(self::getField($officeElement, 'name', $office))
            ->setShortName(self::getField($officeElement, 'shortname', $office))
            ->setTouched(self::getField($officeElement, 'touched', $office))
            ->setUser(self::parseObjectAttribute('User', $office, $officeElement, 'user', array('name' => 'setName', 'shortname' => 'setShortName')));

        // Return the complete object
        return $office;
    }

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