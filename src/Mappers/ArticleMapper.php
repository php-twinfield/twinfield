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
        $articleElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $article->setResult($articleElement->getAttribute('result'))
            ->setStatus($articleElement->getAttribute('status'));

        // Article elements and their methods
        $articleTags = [
            'allowchangeunitsprice'      => 'setAllowChangeUnitsPrice',
            'allowchangevatcode'         => 'setAllowChangeVatCode',
            'allowdecimalquantity'       => 'setAllowDecimalQuantity',
            'allowdiscountorpremium'     => 'setAllowDiscountorPremium',
            'code'                       => 'setCode',
            'name'                       => 'setName',
            'office'                     => 'setOffice',
            'percentage'                 => 'setPercentage',
            'shortname'                  => 'setShortName',
            'type'                       => 'setType',
            'unitnamesingular'           => 'setUnitNameSingular',
            'unitnameplural'             => 'setUnitNamePlural',
            'vatcode'                    => 'setVatCode',
        ];

        // Loop through all the tags
        foreach ($articleTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$article, $method]);
        }

        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            // Element tags and their methods for lines
            $lineTags = [
                'freetext1'       => 'setFreeText1',
                'freetext2'       => 'setFreeText2',
                'freetext3'       => 'setFreeText3',
                'units'           => 'setUnits',
                'name'            => 'setName',
                'shortname'       => 'setShortName',
                'subcode'         => 'setSubCode',
                'unitspriceexcl'  => 'setUnitsPriceExcl',
                'unitspriceinc'   => 'setUnitsPriceInc',
            ];

            $linesDOM = $linesDOMTag->item(0);

            // Loop through each returned line for the article
            foreach ($linesDOM->childNodes as $lineDOM) {

                // Make a new tempory ArticleLine class
                $articleLine = new ArticleLine();

                // Set the attributes (id, inuse, status)
                $articleLine->setID($lineDOM->getAttribute('id'))
                    ->setInUse($lineDOM->getAttribute('inuse'))
                    ->setStatus($lineDOM->getAttribute('status'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($lineTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $lineDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $articleLine->$method($_tag->textContent);
                    }
                }

                // Add the line to the article
                $article->addLine($articleLine);

                // Clean that memory!
                unset ($articleLine);
            }
        }

        return $article;
    }
}
