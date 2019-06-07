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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ProjectsDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
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
        $projectElement = $this->createElement('dimension');
        $this->rootElement->appendChild($projectElement);

        $status = $project->getStatus();

        if (!empty($status)) {
            $projectElement->setAttribute('status', $status);
        }
        
        if (!empty($project->getCode())) {
            $projectElement->appendChild($this->createNodeWithTextContent('code', $project->getCode()));
        }
        
        $projectElement->appendChild($this->createNodeWithTextContent('name', $project->getName()));
        $projectElement->appendChild($this->createNodeWithTextContent('office', $project->getOfficeToString()));
        $projectElement->appendChild($this->createNodeWithTextContent('shortname', $project->getShortName()));
        $projectElement->appendChild($this->createNodeWithTextContent('type', $project->getTypeToString()));

        $financialsElement = $this->createElement('financials');
        $projectElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', $project->getVatCodeToString()));

        $projects = $project->getProjects();

        $projectsElement = $this->createElement('projects');
        $projectElement->appendChild($projectsElement);

        $inheritArray = array('authoriser' => 'getAuthoriserToString', 'billable' => 'getBillableToString', 'customer' => 'getCustomerToString', 'rate' => 'getRateToString');

        foreach ($inheritArray as $inheritVar => $method) {
            $methodLocked = "get" . ucfirst($inheritVar) . "Locked";
            $methodLockedString = $methodLocked . "ToString";
            $methodInherit = "get" . ucfirst($inheritVar) . "Inherit";
            $methodInheritString = $methodInherit . "ToString";
            $elementVar = "";

            if ($projects->$methodLocked() != true || $projects->$methodInherit() != true) {
                $elementVar = $projects->$method();
            } elseif ($inheritVar == 'billable') {
                $projects->setBillableForRatio(false);
            }

            $attributesArray = array('locked' => $methodLockedString, 'inherit' => $methodInheritString);

            if ($inheritVar == 'billable') {
                $attributesArray['forratio'] = 'getBillableForRatioToString';
            }

            $projectsElement->appendChild($this->createNodeWithTextContent($inheritVar, $elementVar, $projects, $attributesArray));
        }

        $projectsElement->appendChild($this->createNodeWithTextContent('invoicedescription', $projects->getInvoiceDescription()));
        $projectsElement->appendChild($this->createNodeWithTextContent('validfrom', $projects->getValidFromToString()));
        $projectsElement->appendChild($this->createNodeWithTextContent('validtill', $projects->getValidTillToString()));

        $quantities = $projects->getQuantities();

        if (!empty($quantities)) {
            // Make quantities element
            $quantitiesElement = $this->createElement('quantities');
            $projectsElement->appendChild($quantitiesElement);

            // Go through each quantity assigned to the project project
            foreach ($quantities as $quantity) {
                // Makes quantity element
                $quantityElement = $this->createElement('quantity');
                $quantitiesElement->appendChild($quantityElement);

                $quantityElement->appendChild($this->createNodeWithTextContent('label', $quantity->getLabel()));
                $quantityElement->appendChild($this->createNodeWithTextContent('rate', $quantity->getRateToString()));
                $quantityElement->appendChild($this->createNodeWithTextContent('billable', $quantity->getBillableToString(), $quantity, array('locked' => 'getBillableLockedToString')));
                $quantityElement->appendChild($this->createNodeWithTextContent('mandatory', $quantity->getMandatoryToString()));
            }
        }
    }
}