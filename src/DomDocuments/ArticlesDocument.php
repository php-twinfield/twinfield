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
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ArticlesDocument extends \DOMDocument
{
    /**
     * Holds the <article> element 
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $articleElement;

    /**
     * Creates the <article> element and adds it to the property
     * articleElement
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->articleElement = $this->createElement('article');
        $this->appendChild($this->articleElement);
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
        // Article->header elements and their methods
        $articleTags = array(
            'office'            => 'getOffice',
            'code'              => 'getCode',
            'type'              => 'getType',
            'name'              => 'getName',
            'shortname'         => 'getShortName',
            'unitnamesingular'  => 'getUnitNameSingular',
            'unitnameplural'    => 'getUnitNamePlural',
            'vatcode'           => 'getVatCode',
            'allowchangevatcode' => 'getAllowChangeVatCode',
            'allowdiscountorpremium' => 'getAllowDiscountorPremium',
            'allowchangeunitsprice' => 'getAllowChangeUnitsPrice',
            'allowdecimalquantity' => 'getAllowDecimalQuantity',
            'performancetype'   => 'getPerformanceType',
            //'allowchangeperformancetype' => 'getAllowChangePerformanceType',
            'percentage'        => 'getPercentage',
        );

        // Make header element
        $headerElement = $this->createElement('header');
        $this->articleElement->appendChild($headerElement);

        $status = $article->getStatus();

        if (!empty($status)) {
            $headerElement->setAttribute('status', $status);
        }

        // Go through each Article element and use the assigned method
        foreach ($articleTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $article->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue ?? '');

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $headerElement->appendChild($element);
        }

        $lines = $article->getLines();

        if (!empty($lines)) {
             // Element tags and their methods for lines
            $lineTags = [
                'unitspriceexcl'  => 'getUnitsPriceExcl',
                'unitspriceinc'   => 'getUnitsPriceInc',
                'units'           => 'getUnits',
                'name'            => 'getName',
                'shortname'       => 'getShortName',
                'subcode'         => 'getSubCode',
                'freetext1'       => 'getFreeText1',
            ];

            // Make addresses element
            $linesElement = $this->createElement('lines');
            $this->articleElement->appendChild($linesElement);

            // Go through each line assigned to the article
            foreach ($lines as $line) {
                // Makes new articleLine element
                $lineElement = $this->createElement('line');
                $linesElement->appendChild($lineElement);

                $status = $line->getStatus();
                $id = $line->getID();
                $inUse = $line->getInUse();

                if (!empty($status)) {
                    $lineElement->setAttribute('status', $status);
                }

                if (!empty($id)) {
                    $lineElement->setAttribute('id', $id);
                }

                if (!empty($inUse)) {
                    $lineElement->setAttribute('inuse', $inUse);
                }

                // Go through each line element and use the assigned method
                foreach ($lineTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($line->$method() ?? '');

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $lineElement->appendChild($element);
                }
            }
        }
    }
}
