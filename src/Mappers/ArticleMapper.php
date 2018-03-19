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
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
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

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('header')->item(0);
        $article->setStatus($dimensionElement->getAttribute('status'));

        // Article elements and their methods
        $articleTags = [
            'code'                       => 'setCode',
            'office'                     => 'setOffice',
            'type'                       => 'setType',
            'name'                       => 'setName',
            'shortname'                  => 'setShortName',
            'unitnamesingular'           => 'setUnitNameSingular',
            'unitnameplural'             => 'setUnitNamePlural',
            'vatcode'                    => 'setVatCode',
            'allowchangevatcode'         => 'setAllowChangeVatCode',
            'performancetype'            => 'setPerformanceType',
            'allowchangeperformancetype' => 'setAllowChangePerformanceType',
            'percentage'                 => 'setPercentage',
            'allowdiscountorpremium'     => 'setAllowDiscountorPremium',
            'allowchangeunitsprice'      => 'setAllowChangeUnitsPrice',
            'allowdecimalquantity'       => 'setAllowDecimalQuantity',
        ];

        // Loop through all the tags
        foreach ($articleTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$article, $method]);
        }

        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            // Element tags and their methods for lines
            $lineTags = [
                'unitspriceexcl'  => 'setUnitsPriceExcl',
                'unitspriceinc'   => 'setUnitsPriceInc',
                'units'           => 'setUnits',
                'name'            => 'setName',
                'shortname'       => 'setShortName',
                'subcode'         => 'setSubCode',
                'freetext1'       => 'setFreeText1',
            ];

            $linesDOM = $linesDOMTag->item(0);

            // Loop through each returned line for the article
            foreach ($linesDOM->getElementsByTagName('line') as $lineDOM) {

                // Make a new tempory ArticleLine class
                $articleLine = new ArticleLine();

                // Set the attributes ( id,status,inuse)
                $articleLine->setID($lineDOM->getAttribute('id'))
                    ->setStatus($lineDOM->getAttribute('status'))
                    ->setInUse($lineDOM->getAttribute('inuse'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($lineTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $lineDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $articleLine->$method($_tag->textContent);
                    }
                }

                // Add the bank to the customer
                $article->addLine($articleLine);

                // Clean that memory!
                unset ($articleLine);
            }
        }
        return $article;
    }
}
