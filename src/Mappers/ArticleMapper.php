<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Article;
use PhpTwinfield\ArticleLine;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Util;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ArticleMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Article entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     * @param \PhpTwinfield\Secure\AuthenticatedConnection $connection
     *
     * @return Article
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response, AuthenticatedConnection $connection)
    {
        // Generate new Article object
        $article = new Article();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/article element
        $articleElement = $responseDOM->documentElement;

        // Set the result attribute
        $article->setResult($articleElement->getAttribute('result'));

        // Get the header element
        $headerElement = $articleElement->getElementsByTagName('header')->item(0);

        // Set the status attribute
        $article->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $headerElement->getAttribute('status')));

        // Set the article elements from the header
        $article->setAllowChangePerformanceType(Util::parseBoolean(self::getField($headerElement, 'allowchangeperformancetype', $article)))
            ->setAllowChangeUnitsPrice(Util::parseBoolean(self::getField($headerElement, 'allowchangeunitsprice', $article)))
            ->setAllowChangeVatCode(Util::parseBoolean(self::getField($headerElement, 'allowchangevatcode', $article)))
            ->setAllowDecimalQuantity(Util::parseBoolean(self::getField($headerElement, 'allowdecimalquantity', $article)))
            ->setAllowDiscountOrPremium(Util::parseBoolean(self::getField($headerElement, 'allowdiscountorpremium', $article)))
            ->setCode(self::getField($headerElement, 'code', $article))
            ->setName(self::getField($headerElement, 'name', $article))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $article, $headerElement, 'office'))
            ->setPercentage(Util::parseBoolean(self::getField($headerElement, 'percentage', $article)))
            ->setPerformanceType(self::parseEnumAttribute(\PhpTwinfield\Enums\PerformanceType::class, self::getField($headerElement, 'performancetype', $article)))
            ->setShortName(self::getField($headerElement, 'shortname', $article))
            ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\ArticleType::class, self::getField($headerElement, 'type', $article)))
            ->setUnitNameSingular(self::getField($headerElement, 'unitnamesingular', $article))
            ->setUnitNamePlural(self::getField($headerElement, 'unitnameplural', $article))
            ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $article, $headerElement, 'vatcode'));
            
        $currencies = self::getOfficeCurrencies($connection, $article->getOffice());

        // Get the lines element
        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            $lineNumber = 0;

            // Loop through each returned line for the article
            foreach ($linesDOMTag->item(0)->childNodes as $lineElement) {
                // Skip child nodes that are not of the DOMElement type
                if ($lineElement->nodeType !== 1) {
                    continue;
                }

                $lineNumber++;

                // Make a new temporary ArticleLine class
                $articleLine = new ArticleLine();

                // Set the ID attributes if it is not null, else set the current line number
                if ($lineElement->getAttribute('id') != null) {
                    $articleLine->setID($lineElement->getAttribute('id'));
                } else {
                    $articleLine->setID($lineNumber);
                }

                // Set the inuse and status attributes
                $articleLine->setInUse($lineElement->getAttribute('inuse'));
                $articleLine->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $lineElement->getAttribute('status')));

                // Set the article line elements
                $articleLine->setFreeText1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $articleLine, $lineElement, 'freetext1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                    ->setFreeText2(self::parseObjectAttribute(\PhpTwinfield\CostCenter::class, $articleLine, $lineElement, 'freetext2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                    ->setFreeText3(self::getField($lineElement, 'freetext3', $articleLine))
                    ->setUnits(self::getField($lineElement, 'units', $articleLine))
                    ->setName(self::getField($lineElement, 'name', $articleLine))
                    ->setShortName(self::getField($lineElement, 'shortname', $articleLine))
                    ->setSubCode(self::getField($lineElement, 'subcode', $articleLine))
                    ->setUnitsPriceExcl(self::parseMoneyAttribute(self::getField($lineElement, 'unitspriceexcl', $articleLine), $currencies['base']))
                    ->setUnitsPriceInc(self::parseMoneyAttribute(self::getField($lineElement, 'unitspriceinc', $articleLine), $currencies['base']));

                // Add the line to the article
                $article->addLine($articleLine);

                // Clean that memory!
                unset ($articleLine);
            }
        }

        // Return the complete object
        return $article;
    }
}