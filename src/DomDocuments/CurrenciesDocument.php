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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
     * @param Currency $currency
     * @return void | [Adds to this instance]
     */
    public function addCurrency(Currency $currency)
    {
        $rootElement = $this->currencyElement;

        $status = $currency->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // Currency elements and their methods
        $currencyTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'shortname'         => 'getShortName',
        );

        // Go through each Currency element and use the assigned method
        foreach ($currencyTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $currency->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $rootElement->appendChild($element);
        }

        $rates = $currency->getRates();

        if (!empty($rates)) {
            // Make rates element
            $ratesElement = $this->createElement('rates');
            $rootElement->appendChild($ratesElement);

            // Element tags and their methods for rates
            $rateTags = [
                'rate'          => 'getRate',
                'startdate'     => 'getStartDate',
            ];

            // Go through each rate assigned to the currency
            foreach ($rates as $rate) {
                // Makes new currencyRate element
                $rateElement = $this->createElement('rate');
                $ratesElement->appendChild($rateElement);

                $status = $rate->getStatus();

                if (!empty($status)) {
                    $rateElement->setAttribute('status', $status);
                }

                // Go through each rate element and use the assigned method
                foreach ($rateTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($rate->$method());

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
