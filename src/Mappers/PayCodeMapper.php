<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\PayCode;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class PayCodeMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean PayCode entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return PayCode
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new PayCode object
        $payCode = new PayCode();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/vat group element
        $payCodeElement = $responseDOM->documentElement;

        // Set the vat group elements from the vat group element
        $payCode->setCode(self::getField($payCode, $payCodeElement, 'code'))
            ->setName(self::getField($payCode, $payCodeElement, 'name'))
            ->setShortName(self::getField($payCode, $payCodeElement, 'shortname'));

        // Return the complete object
        return $payCode;
    }
}
