<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Currency;

/**
 * The Document Holder for making new XML Currency. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Currency.
 *
 * @package PhpTwinfield
 * @subpackage Currency\DOM
 *
 * This class is created by copying and changing the Article class
 *
 * @author Marc van de Geijn <marc@bhosted.nl>
 */
class CurrenciesDocument extends \DOMDocument
{
    /**
     * Holds the <currency> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $currencyElement;

    /**
     * Creates the <currency> element and adds it to the property
     * currencyElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->currencyElement = $this->createElement('currency');
        $this->appendChild($this->currencyElement);
    }

    /**
     * Turns a passed Currency class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Currency to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Currency $article
     * @return void | [Adds to this instance]
     */
    public function addCurrency(Currency $currency)
    {
        // Currency->header elements and their methods
        $currencyTags = array(
            'office'            => 'getOfficeCode',
            'code'              => 'getCode',
            'name'              => 'getName',
            'shortname'         => 'getShortName',
        );

        // Go through each Currency element and use the assigned method
        foreach ($currencyTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $currency->$method();

            if( ! empty( $nodeValue) ) {
                $node = $this->createTextNode($nodeValue);

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $this->currencyElement->appendChild($element);
            }
        }

        $rates = $currency->getRates();

        if (!empty($rates)) {
             // Element tags and their methods for lines
            $rateTags = [
                'startdate' => 'getStartdate',
                'rate'      => 'getRate'
            ];

            // Make addresses element
            $ratesElement = $this->createElement('rates');
            $this->currencyElement->appendChild($ratesElement);

            // Go through each line assigned to the currency
            foreach ($rates as $line) {
                // Makes new currencyLine element
                $rateElement = $this->createElement('rate');
                $ratesElement->appendChild($rateElement);

                if( ! empty($line->getStatus() ) ) {
                    $statusElement = $this->createElement($line->getStatus());
                    $element = $this->createElement('status');
                    $element->appendChild($statusElement);
                    $rateElement->appendChild($element);
                }

                // Go through each line element and use the assigned method
                foreach ($rateTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($line->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $rateElement->appendChild($element);
                }
            }
        }
    }
}
