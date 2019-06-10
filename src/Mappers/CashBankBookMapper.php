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
        $cashBankBook = new CashBankBook();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/cash or bank book element
        $cashBankBookElement = $responseDOM->documentElement;

        // Set the cash or bank book elements from the cash or bank book element
        $cashBankBook->setCode(self::getField($cashBankBookElement, 'code', $cashBankBook))
            ->setName(self::getField($cashBankBookElement, 'name', $cashBankBook))
            ->setShortName(self::getField($cashBankBookElement, 'shortname', $cashBankBook));

        // Return the complete object
        return $cashBankBook;
    }
}