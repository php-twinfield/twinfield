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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ActivitiesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
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
        $activityElement = $this->createElement('dimension');
        $this->rootElement->appendChild($activityElement);

        $status = $activity->getStatus();

        if (!empty($status)) {
            $activityElement->setAttribute('status', $status);
        }

        $activityElement->appendChild($this->createNodeWithTextContent('code', $activity->getCode()));
        $activityElement->appendChild($this->createNodeWithTextContent('name', $activity->getName()));
        $activityElement->appendChild($this->createNodeWithTextContent('office', $activity->getOfficeToCode()));
        $activityElement->appendChild($this->createNodeWithTextContent('shortname', $activity->getShortName()));
        $activityElement->appendChild($this->createNodeWithTextContent('type', $activity->getTypeToCode()));

        $financialsElement = $this->createElement('financials');
        $activityElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', $activity->getVatCodeToCode()));

        $projects = $activity->getProjects();

        $projectsElement = $this->createElement('projects');
        $activityElement->appendChild($projectsElement);

        $inheritArray = array('authoriser' => 'getAuthoriserToCode', 'billable' => 'getBillableToString', 'customer' => 'getCustomerToCode', 'rate' => 'getRateToCode');

        foreach ($inheritArray as $inheritVar => $method) {
            $methodLocked = "get" . ucfirst($inheritVar) . "Locked";
            $methodInherit = "get" . ucfirst($inheritVar) . "Inherit";
            $elementVar = "";

            if ($projects->$methodLocked() !== true || $projects->$methodInherit() !== true) {
                $elementVar = $projects->$method();
            } elseif ($inheritVar == 'billable') {
                $projects->setBillableForRatio(false);
            }

            $attributesArray = array('locked' => $methodLocked, 'inherit' => $methodInherit);

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

            // Go through each quantity assigned to the activity project
            foreach ($quantities as $quantity) {
                // Makes quantity element
                $quantityElement = $this->createElement('quantity');
                $quantitiesElement->appendChild($quantityElement);

                $quantityElement->appendChild($this->createNodeWithTextContent('label', $quantity->getLabel()));
                $quantityElement->appendChild($this->createNodeWithTextContent('rate', $quantity->getRateToCode()));
                $quantityElement->appendChild($this->createNodeWithTextContent('billable', $quantity->getBillableToString(), $quantity, array('locked' => 'getBillableLockedToString')));
                $quantityElement->appendChild($this->createNodeWithTextContent('mandatory', $quantity->getMandatoryToString()));
            }
        }
    }
}