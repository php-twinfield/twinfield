<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\InvoiceType;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class InvoiceTypeMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean InvoiceType entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return InvoiceType
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new InvoiceType object
        $invoiceType = new InvoiceType();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/invoice type element
        $invoiceTypeElement = $responseDOM->documentElement;

        // Set the vat group elements from the vat group element
        $invoiceType->setCode(self::getField($invoiceType, $invoiceTypeElement, 'code'))
            ->setName(self::getField($invoiceType, $invoiceTypeElement, 'name'))
            ->setShortName(self::getField($invoiceType, $invoiceTypeElement, 'shortname'));

        // Return the complete object
        return $invoiceType;
    }
}