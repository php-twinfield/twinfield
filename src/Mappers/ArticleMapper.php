<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Article;
use PhpTwinfield\ArticleLine;
use PhpTwinfield\Response\Response;

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
     *
     * @return Article
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
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
        $article->setStatus(self::parseEnumAttribute('Status', $headerElement->getAttribute('status')));

        // Set the article elements from the header
        $article->setAllowChangePerformanceType(self::parseBooleanAttribute(self::getField($article, $headerElement, 'allowchangeperformancetype')))
            ->setAllowChangeUnitsPrice(self::parseBooleanAttribute(self::getField($article, $headerElement, 'allowchangeunitsprice')))
            ->setAllowChangeVatCode(self::parseBooleanAttribute(self::getField($article, $headerElement, 'allowchangevatcode')))
            ->setAllowDecimalQuantity(self::parseBooleanAttribute(self::getField($article, $headerElement, 'allowdecimalquantity')))
            ->setAllowDiscountOrPremium(self::parseBooleanAttribute(self::getField($article, $headerElement, 'allowdiscountorpremium')))
            ->setCode(self::getField($article, $headerElement, 'code'))
            ->setName(self::getField($article, $headerElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $article, $headerElement, 'office'))
            ->setPercentage(self::parseBooleanAttribute(self::getField($article, $headerElement, 'percentage')))
            ->setPerformanceType(self::parseEnumAttribute('PerformanceType', self::getField($article, $headerElement, 'performancetype')))
            ->setShortName(self::getField($article, $headerElement, 'shortname'))
            ->setType(self::parseEnumAttribute('ArticleType', self::getField($article, $headerElement, 'type')))
            ->setUnitNameSingular(self::getField($article, $headerElement, 'unitnamesingular'))
            ->setUnitNamePlural(self::getField($article, $headerElement, 'unitnameplural'))
            ->setVatCode(self::parseObjectAttribute('VatCode', $article, $headerElement, 'vatcode'));

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
                $articleLine->setStatusFromString($lineElement->getAttribute('status'));

                // Set the article line elements
                $articleLine->setFreeText1(self::parseObjectAttribute('GeneralLedger', $articleLine, $lineElement, 'freetext1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                    ->setFreeText2(self::parseObjectAttribute('CostCenter', $articleLine, $lineElement, 'freetext2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                    ->setFreeText3(self::getField($articleLine, $lineElement, 'freetext3'))
                    ->setUnits(self::getField($articleLine, $lineElement, 'units'))
                    ->setName(self::getField($articleLine, $lineElement, 'name'))
                    ->setShortName(self::getField($articleLine, $lineElement, 'shortname'))
                    ->setSubCode(self::getField($articleLine, $lineElement, 'subcode'))
                    ->setUnitsPriceExcl(self::parseMoneyAttribute(self::getField($articleLine, $lineElement, 'unitspriceexcl')))
                    ->setUnitsPriceInc(self::parseMoneyAttribute(self::getField($articleLine, $lineElement, 'unitspriceinc')));

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