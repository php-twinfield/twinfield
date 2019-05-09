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
class CurrenciesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "currencies";
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
        $currencyElement = $this->createElement('currency');
        $this->rootElement->appendChild($currencyElement);

        $status = $currency->getStatus();

        if (!empty($status)) {
            $currencyElement->setAttribute('status', $status);
        }

        $currencyElement->appendChild($this->createNodeWithTextContent('code', $currency->getCode()));
        $currencyElement->appendChild($this->createNodeWithTextContent('name', $currency->getName()));
        $currencyElement->appendChild($this->createNodeWithTextContent('office', $currency->getOfficeToCode()));
        $currencyElement->appendChild($this->createNodeWithTextContent('shortname', $currency->getShortName()));

        $rates = $currency->getRates();

        if (!empty($rates)) {
            // Make rates element
            $ratesElement = $this->createElement('rates');
            $currencyElement->appendChild($ratesElement);

            // Go through each rate assigned to the currency
            foreach ($rates as $rate) {
                // Makes rate element
                $rateElement = $this->createElement('rate');
                $ratesElement->appendChild($rateElement);

                $status = $rate->getStatus();

                if (!empty($status)) {
                    $rateElement->setAttribute('status', $status);
                }

                $rateElement->appendChild($this->createNodeWithTextContent('rate', $rate->getRate()));
                $rateElement->appendChild($this->createNodeWithTextContent('startdate', $rate->getStartDateToString()));
            }
        }
    }
}
