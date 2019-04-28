<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Project;
use PhpTwinfield\ProjectProjects;
use PhpTwinfield\ProjectQuantity;
use PhpTwinfield\Customer;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ProjectMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean Project entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return Project
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Project object
        $project = new Project();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $projectElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $project->setResult($projectElement->getAttribute('result'));
        $project->setStatus($projectElement->getAttribute('status'));

        // Project elements and their methods
        $projectTags = array(
            'behaviour'         => 'setBehaviour',
            'code'              => 'setCode',
            'inuse'             => 'setInUse',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'shortname'         => 'setShortName',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
            'vatcode'           => 'setVatCode',
        );

        // Loop through all the tags
        foreach ($projectTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$project, $method]);
        }

        // Make a ProjectProjects element and loop through custom tags
        $projectsTags = array(
            'authoriser'                => 'setAuthoriser',
            'billable'                  => 'setBillable',
            'invoicedescription'        => 'setInvoiceDescription',
            'rate'                      => 'setRate',
            'validfrom'                 => 'setValidFrom',
            'validtill'                 => 'setValidTill',
        );

        $projectProjects = new ProjectProjects();

        $projectsElement = $responseDOM->getElementsByTagName('projects')->item(0);

        if ($projectsElement !== null) {
            // Go through each projects element and add to the assigned method
            foreach ($projectsTags as $tag => $method) {
                $projectProjects->$method(self::getField($projectsElement, $tag));
            }

            $customercode = $responseDOM->getElementsByTagName('customer')->item(0);

            if (isset($customercode) && isset($customercode->textContent)) {
                $customer = new Customer();
                $customer->setCode($customercode->textContent);
                $projectProjects->setCustomer($customer);
            }

            $quantitiesDOMTag = $projectsElement->getElementsByTagName('quantities');

            if (isset($quantitiesDOMTag) && $quantitiesDOMTag->length > 0) {
                // Element tags and their methods for quantities
                $quantityTags = [
                    'billable'      => 'setBillable',
                    'label'         => 'setLabel',
                    'mandatory'     => 'setMandatory',
                    'rate'          => 'setRate',
                ];

                $quantitiesDOM = $quantitiesDOMTag->item(0);

                // Loop through each returned quantity for the project
                foreach ($quantitiesDOM->childNodes as $quantityDOM) {
                    // Make a new tempory ProjectQuantity class
                    $projectQuantity = new ProjectQuantity();

                    // Loop through the element tags. Determine if it exists and set it if it does
                    foreach ($quantityTags as $tag => $method) {
                        // Get the dom element
                        $_tag = $quantityDOM->getElementsByTagName($tag)->item(0);

                        // Check if the tag is set, and its content is set, to prevent DOMNode errors
                        if (isset($_tag) && isset($_tag->textContent)) {
                            $projectQuantity->$method($_tag->textContent);
                        }
                    }

                    // Add the quantity to the project
                    $projectProjects->addQuantity($projectQuantity);

                    // Clean that memory!
                    unset ($projectQuantity);
                }
            }
        }

        // Set the custom class to the project
        $project->setProjects($projectProjects);

        return $project;
    }
}
