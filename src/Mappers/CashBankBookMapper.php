<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\CashBankBook;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CashBankBookMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean CashBankBook entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return CashBankBook
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new CashBankBook object
        $vatGroup = new CashBankBook();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/vat group element
        $vatGroupElement = $responseDOM->documentElement;

        // Set the vat group elements from the vat group element
        $vatGroup->setCode(self::getField($vatGroup, $vatGroupElement, 'code'))
            ->setName(self::getField($vatGroup, $vatGroupElement, 'name'))
            ->setShortName(self::getField($vatGroup, $vatGroupElement, 'shortname'));

        // Return the complete object
        return $vatGroup;
    }
}