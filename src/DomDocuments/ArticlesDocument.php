<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Article;
use PhpTwinfield\Util;

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

        $headerElement->appendChild($this->createNodeWithTextContent('allowchangeperformancetype', Util::formatBoolean($article->getAllowChangePerformanceType())));
        $headerElement->appendChild($this->createNodeWithTextContent('allowchangeunitsprice', Util::formatBoolean($article->getAllowChangeUnitsPrice())));
        $headerElement->appendChild($this->createNodeWithTextContent('allowchangevatcode', Util::formatBoolean($article->getAllowChangeVatCode())));
        $headerElement->appendChild($this->createNodeWithTextContent('allowdecimalquantity', Util::formatBoolean($article->getAllowDecimalQuantity())));
        $headerElement->appendChild($this->createNodeWithTextContent('allowdiscountorpremium', Util::formatBoolean($article->getAllowDiscountOrPremium())));
        $headerElement->appendChild($this->createNodeWithTextContent('code', $article->getCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($article->getOffice())));
        $headerElement->appendChild($this->createNodeWithTextContent('name', $article->getName()));
        $headerElement->appendChild($this->createNodeWithTextContent('percentage', Util::formatBoolean($article->getPercentage())));
        $headerElement->appendChild($this->createNodeWithTextContent('performancetype', $article->getPerformanceType()));
        $headerElement->appendChild($this->createNodeWithTextContent('shortname', $article->getShortName()));
        $headerElement->appendChild($this->createNodeWithTextContent('type', $article->getType()));
        $headerElement->appendChild($this->createNodeWithTextContent('unitnameplural', $article->getUnitNamePlural()));
        $headerElement->appendChild($this->createNodeWithTextContent('unitnamesingular', $article->getUnitNameSingular()));
        $headerElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($article->getVatCode())));

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

                $lineElement->appendChild($this->createNodeWithTextContent('freetext1', Util::objectToStr($line->getFreeText1())));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext2', Util::objectToStr($line->getFreeText2())));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext3', $line->getFreeText3()));
                $lineElement->appendChild($this->createNodeWithTextContent('name', $line->getName()));
                $lineElement->appendChild($this->createNodeWithTextContent('shortname', $line->getShortName()));
                $lineElement->appendChild($this->createNodeWithTextContent('subcode', $line->getSubCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('units', $line->getUnits()));
                $lineElement->appendChild($this->createNodeWithTextContent('unitspriceexcl', Util::formatMoney($line->getUnitsPriceExcl())));
                $lineElement->appendChild($this->createNodeWithTextContent('unitspriceinc', Util::formatMoney($line->getUnitsPriceInc())));
            }
        }
    }
}
