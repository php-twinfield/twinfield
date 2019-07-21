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

        // Set the invoice type elements from the invoice type element
        $invoiceType->setCode(self::getField($invoiceTypeElement, 'code', $invoiceType))
            ->setName(self::getField($invoiceTypeElement, 'name', $invoiceType))
            ->setShortName(self::getField($invoiceTypeElement, 'shortname', $invoiceType));

        // Return the complete object
        return $invoiceType;
    }
}
