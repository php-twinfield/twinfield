<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Project;

class ProjectDocument extends BaseDocument
{
    /**
     * The root tag, e.g.
     *
     * @return string
     */
    protected function getRootTagName(): string
    {
        return 'dimension';
    }

    /**
     * Turns a passed Project class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the transaction
     * to this DOMDocument instance for submission usage.
     *
     * @param Project $project
     */
    public function addProject(Project $project)
    {
        $office = $project->getOffice();

        // Transaction->status
        $this->rootElement->setAttribute('status', $project->getStatus());

        // Transaction > Office
        if(!is_null($office)) {
            $officeElement = $this->createNodeWithTextContent('office', $office->getCode());
            $this->rootElement->appendChild($officeElement);
        }

        // Transaction > Type
        $typeElement = $this->createNodeWithTextContent('type', 'PRJ');
        $this->rootElement->appendChild($typeElement);

        // Transaction > Code
        if(!is_null($project->getCode())) {
            $codeElement = $this->createNodeWithTextContent('code', $project->getCode());
            $this->rootElement->appendChild($codeElement);
        }

        // Transaction > Name
        $nameElement = $this->createNodeWithTextContent('name', $project->getName());
        $this->rootElement->appendChild($nameElement);

        // Transaction > Short name
        $shortNameElement = $this->createNodeWithTextContent('shortname', $project->getShortName());
        $this->rootElement->appendChild($shortNameElement);

        // Transaction > Projects
        $projectsElement = $this->createElement('projects');

        // Transaction > Projects > Invoice description
        if(!is_null($project->getInvoiceDescription())) {
            $invoiceDescriptionElement = $this->createNodeWithTextContent('invoicedescription',
                $project->getInvoiceDescription());
            $projectsElement->appendChild($invoiceDescriptionElement);
        }

        // Transaction > Projects > authoriser
        // Todo: set the attributes on the authoriser element
        $authoriserElement = $this->createNodeWithTextContent('authoriser', $project->getAuthoriser());
        $projectsElement->appendChild($authoriserElement);

        // Transaction > Projects > customer
        // Todo: set the attributes on the customer element
        $customerElement = $this->createNodeWithTextContent('customer', $project->getCustomer());
        $projectsElement->appendChild($customerElement);

        // Transaction > Projects > billable
        // Todo: set the attributes on the billable element
        $billableElement = $this->createNodeWithTextContent('billable', $project->getBillable());
        $projectsElement->appendChild($billableElement);

        // Transaction > Projects > rate
        // Todo: set the attributes on the rate element
        if(!is_null($project->getRate())) {
            $rateElement = $this->createNodeWithTextContent('rate', $project->getRate());
            $projectsElement->appendChild($rateElement);
        }

        // Transaction > Projects > Quantities
        if(!is_null($project->getQuantities())) {
            $projectsElement->appendChild($this->createQuantityElement($project));
        }
    }

    /**
     * @param Project $project
     * @return \DOMElement
     */
    private function createQuantityElement(Project $project): \DOMElement
    {
        $quantityTags = [
            'label',
            'rate',
            'billable',
            'mandatory',
        ];

        $quantitiesElement = $this->createElement('quantities');

        foreach ($project->getQuantities() as $quantity) {
            // Create quantity tags
            foreach ($quantityTags as $tag) {
                // Get value
                $getter = 'get' . ucfirst($tag);
                $value = $quantity->{$getter}();
                if(is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                }

                $tagElement = $this->createNodeWithTextContent($tag, $value);
                $quantitiesElement->appendChild($tagElement);
            }
        }

        return $quantitiesElement;
    }

}
