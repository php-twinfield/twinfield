<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Article;

/**
 * The Document Holder for making new XML Article. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Article.
 *
 * @package PhpTwinfield
 * @subpackage Article\DOM
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ArticlesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "articles";
    }

    /**
     * Turns a passed Article class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Article to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Article $article
     * @return void | [Adds to this instance]
     */
    public function addArticle(Article $article)
    {
        $articleElement = $this->createElement('article');
        $this->rootElement->appendChild($articleElement);

        // Make header element
        $headerElement = $this->createElement('header');
        $articleElement->appendChild($headerElement);

        $status = $article->getStatus();

        if (!empty($status)) {
            $headerElement->setAttribute('status', $status);
        }

        $headerElement->appendChild($this->createNodeWithTextContent('allowchangeperformancetype', $article->getAllowChangePerformanceTypeToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('allowchangeunitsprice', $article->getAllowChangeUnitsPriceToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('allowchangevatcode', $article->getAllowChangeVatCodeToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('allowdecimalquantity', $article->getAllowDecimalQuantityToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('allowdiscountorpremium', $article->getAllowDiscountOrPremiumToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('code', $article->getCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('office', $article->getOfficeToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('name', $article->getName()));
        $headerElement->appendChild($this->createNodeWithTextContent('percentage', $article->getPercentageToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('performancetype', $article->getPerformanceType()));
        $headerElement->appendChild($this->createNodeWithTextContent('shortname', $article->getShortName()));
        $headerElement->appendChild($this->createNodeWithTextContent('type', $article->getType()));
        $headerElement->appendChild($this->createNodeWithTextContent('unitnameplural', $article->getUnitNamePlural()));
        $headerElement->appendChild($this->createNodeWithTextContent('unitnamesingular', $article->getUnitNameSingular()));
        $headerElement->appendChild($this->createNodeWithTextContent('vatcode', $article->getVatCodeToCode()));

        //Clear VAT code in case of a discount/premium article with percentage set to true to prevent errors
        if ($article->getType() != "normal" && $article->getPercentage() == true) {
            $headerElement->getElementsByTagName('vatcode')->item(0)->nodeValue = "";
        }

        $lines = $article->getLines();

        if (!empty($lines)) {
            // Make lines element
            $linesElement = $this->createElement('lines');
            $articleElement->appendChild($linesElement);

            // Go through each line assigned to the article
            foreach ($lines as $line) {
                // Makes new line element
                $lineElement = $this->createElement('line');
                $linesElement->appendChild($lineElement);

                $id = $line->getID();

                if (!empty($id)) {
                    $lineElement->setAttribute('id', $id);
                }

                $status = $line->getStatus();

                if (!empty($status)) {
                    $lineElement->setAttribute('status', $status);
                }

                $lineElement->appendChild($this->createNodeWithTextContent('freetext1', $line->getFreeText1ToCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext2', $line->getFreeText2ToCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext3', $line->getFreeText3()));
                $lineElement->appendChild($this->createNodeWithTextContent('name', $line->getName()));
                $lineElement->appendChild($this->createNodeWithTextContent('shortname', $line->getShortName()));
                $lineElement->appendChild($this->createNodeWithTextContent('subcode', $line->getSubCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('units', $line->getUnits()));
                $lineElement->appendChild($this->createNodeWithTextContent('unitspriceexcl', $line->getUnitsPriceExclToFloat()));
                $lineElement->appendChild($this->createNodeWithTextContent('unitspriceinc', $line->getUnitsPriceIncToFloat()));
            }
        }
    }
}
