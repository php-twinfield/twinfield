<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Activity;

/**
 * The Document Holder for making new XML Activity. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Activity.
 *
 * @package PhpTwinfield
 * @subpackage Activity\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ActivitiesDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $activityElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * activityElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->activityElement = $this->createElement('dimension');
        $this->appendChild($this->activityElement);
    }

    /**
     * Turns a passed Activity class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Activity to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Activity $activity
     * @return void | [Adds to this instance]
     */
    public function addActivity(Activity $activity)
    {
        $rootElement = $this->activityElement;

        $status = $activity->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // Activity elements and their methods
        $activityTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'shortname'         => 'getShortName',
            'type'              => 'getType',
        );

        // Go through each Activity element and use the assigned method
        foreach ($activityTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $activity->$method();
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

        $vatCodenode = $this->createTextNode($activity->getVatCode());
        $vatcodeElement = $this->createElement('vatcode');
        $vatcodeElement->appendChild($vatCodenode);
        $financialsElement = $this->createElement('financials');
        $financialsElement->appendChild($vatcodeElement);
        $rootElement->appendChild($financialsElement);

        // ActivityProjects element and its methods
        $projectsTags = array(
            'authoriser'                => 'getAuthoriser',
            'billable'                  => 'getBillable',
            'invoicedescription'        => 'getInvoiceDescription',
            'rate'                      => 'getRate',
            'validfrom'                 => 'getValidFrom',
            'validtill'                 => 'getValidTill',
        );

        $projects = $activity->getProjects();

        $projectsElement = $this->createElement('projects');
        $rootElement->appendChild($projectsElement);

        // Go through each ActivityProjects element and use the assigned method
        foreach ($projectsTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $projects->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $projectsElement->appendChild($element);
        }

        $customer = $projects->getCustomer();
        $customerNode = $this->createTextNode($customer->getCode());
        $customerElement = $this->createElement('customer');
        $customerElement->appendChild($customerNode);
        $projectsElement->appendChild($customerElement);

        $quantities = $projects->getQuantities();

        if (!empty($quantities)) {
             // Element tags and their methods for quantities
            $quantityTags = [
                'billable'      => 'getBillable',
                'label'         => 'getLabel',
                'mandatory'     => 'getMandatory',
                'rate'          => 'getRate',
            ];

            // Make quantities element
            $quantitiesElement = $this->createElement('quantities');
            $projectsElement->appendChild($quantitiesElement);

            // Go through each quantity assigned to the project
            foreach ($quantities as $quantity) {
                // Makes new quantity element
                $quantityElement = $this->createElement('quantity');
                $quantitiesElement->appendChild($quantityElement);

                // Go through each quantity element and use the assigned method
                foreach ($quantityTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($quantity->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $quantityElement->appendChild($element);
                }
            }
        }
    }
}
