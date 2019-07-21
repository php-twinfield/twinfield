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

        // Get the root/paycode element
        $payCodeElement = $responseDOM->documentElement;

        // Set the paycode elements from the paycode element
        $payCode->setCode(self::getField($payCodeElement, 'code', $payCode))
            ->setName(self::getField($payCodeElement, 'name', $payCode))
            ->setShortName(self::getField($payCodeElement, 'shortname', $payCode));

        // Return the complete object
        return $payCode;
    }
}
