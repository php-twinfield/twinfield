<?php
namespace Pronamic\Twinfield\Article\DOM;

use \Pronamic\Twinfield\Article\Article;

/**
 * The Document Holder for making new XML Article. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Article.
 * 
 * @package Pronamic\Twinfield
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
     * @param \Pronamic\Twinfield\Article\Article $article
     * @return void | [Adds to this instance]
     */
    public function addArticle(Article $article)
    {
        // Article->header elements and their methods
        $articleTags = array(
            'code'              => 'getCode',
            'office'            => 'getOffice',
            'type'              => 'getType',
            'name'              => 'getName',
            'shortname'         => 'getShortName',
            'unitnamesingular'  => 'getUnitNameSingular',
            'unitnameplural'    => 'getUnitNamePlural',
            'vatnumber'         => 'getVatNumber',
            'allowchangevatcode' => 'getAllowChangeVatCode',
            'performancetype'   => 'getPerformanceType',
            'allowchangeperformancetype' => 'getAllowChangePerformanceType',
            'percentage'        => 'getPercentage',
            'allowdiscountorpremium' => 'getAllowDiscountorPremium',
            'allowchangeunitsprice' => 'getAllowChangeUnitsPrice',
            'allowdecimalquantity' => 'getAllowDecimalQuantity',
            
        );
        
        // Make header element
        $addressesElement = $this->createElement('header');
        $this->dimensionElement->appendChild($headerElement);
        
        $status = $article->getStatus();
        
        if (!empty($status))
            $headerElement->setAttribute('status', $status);

        // Go through each Article element and use the assigned method
        foreach($articleTags as $tag => $method ) {
            
            // Make text node for method value
            $node = $this->createTextNode($article->$method());
            
            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);
            
            // Add the full element
            $headerElement->appendChild($element);
        }

        $lines = $article->getLines();
        if (!empty($Lines)) {

             // Element tags and their methods for lines
            $lineTags = array(
                'unitspriceexcl'  => 'getUnitsPriceExcl',
                'unitspriceinc'   => 'getUnitsPriceInc',
                'units'           => 'getUnits',
                'name'            => 'getName',
                'shortname'       => 'getShortName',
                'subcode'         => 'getSubCode',
                'freetext1'       => 'getFreeText1',
            );
            
            // Make addresses element
            $linesElement = $this->createElement('lines');
            $this->dimensionElement->appendChild($linesElement);

            // Go through each line assigned to the article
            foreach($lines as $line) {

                // Makes new articleLine element
                $lineElement = $this->createElement('line');
                $linesElement->appendChild($lineElement);

                // Set attributes
                $lineElement->setAttribute('status', $line->getStatus());
                $lineElement->setAttribute('id', $line->getID());
                $lineElement->setAttribute('inuse', $line->getInUse());

                // Go through each line element and use the assigned method
                foreach($lineTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($line->$method());

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
