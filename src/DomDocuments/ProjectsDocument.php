<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Project;

/**
 * The Document Holder for making new XML Project. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Project.
 *
 * @package PhpTwinfield
 * @subpackage Project\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ProjectsDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $projectElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * projectElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->projectElement = $this->createElement('dimension');
        $this->appendChild($this->projectElement);
    }

    /**
     * Turns a passed Project class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Project to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Project $project
     * @return void | [Adds to this instance]
     */
    public function addProject(Project $project)
    {
        $rootElement = $this->projectElement;

        $status = $project->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // Project elements and their methods
        $projectTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'shortname'         => 'getShortName',
            'type'              => 'getType',
        );

        // Go through each Project element and use the assigned method
        foreach ($projectTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $project->$method();
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

        $vatCodenode = $this->createTextNode($project->getVatCode());
        $vatcodeElement = $this->createElement('vatcode');
        $vatcodeElement->appendChild($vatCodenode);
        $financialsElement = $this->createElement('financials');
        $financialsElement->appendChild($vatcodeElement);
        $rootElement->appendChild($financialsElement);

        $projects = $project->getProjects();

        $projectsElement = $this->createElement('projects');
        $rootElement->appendChild($projectsElement);

        // ProjectProjects element and its methods
        $projectsTags = array(
            'authoriser'                => 'getAuthoriser',
            'billable'                  => 'getBillable',
            'invoicedescription'        => 'getInvoiceDescription',
            'rate'                      => 'getRate',
            'validfrom'                 => 'getValidFrom',
            'validtill'                 => 'getValidTill',
        );

        // Go through each ProjectProjects element and use the assigned method
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
            // Make quantities element
            $quantitiesElement = $this->createElement('quantities');
            $projectsElement->appendChild($quantitiesElement);

            // Element tags and their methods for quantities
            $quantityTags = [
                'billable'      => 'getBillable',
                'label'         => 'getLabel',
                'mandatory'     => 'getMandatory',
                'rate'          => 'getRate',
            ];

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
