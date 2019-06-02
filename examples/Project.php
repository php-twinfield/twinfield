<?php

/* Project
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Projects/Manage
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Project API Connector
 * \PhpTwinfield\ApiConnectors\ProjectApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$projectApiConnector = new \PhpTwinfield\ApiConnectors\ProjectApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all projects
 * @param string $pattern  The search pattern. May contain wildcards * and ?
 * @param int    $field    The search field determines which field or fields will be searched. The available fields
 *                         depends on the finder type. Passing a value outside the specified values will cause an
 *                         error. 0 searches on the code or name field, 1 searches only on the code field,
 *                         2 searches only on the name field, 3 searches only for the customer code
 * @param int    $firstRow First row to return, useful for paging
 * @param int    $maxRows  Maximum number of rows to return, useful for paging
 * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
 *                         to add multiple options. An option name may be used once, specifying an option multiple
 *                         times will cause an error.
 *
 *                         Available options:      office, accessrules, accessuser, role, trscode
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         accessrules             Disabling the access rules is only possible if the user has access to project dimension maintenance.
 *                         Available values:       0, 1
 *                         Usage:                  $options['accessrules'] = '0';
 *
 *                         accessuser              Specifies the time user.
 *                         Usage:                  $options['accessuser'] = 'TWINAPPS';
 *
 *                         role                    Specifies the time user role.
 *                         Usage:                  $options['role'] = 'LVL1';
 *
 *                         trscode                 Specifies the transaction code and will only return the projects or projects which are related to the specified transaction code.
 *                         Usage:                  $options['trscode'] = '20190001';
 *
 */

//List all with pattern "P*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> accessrules = 0
if ($executeListAllWithFilter) {
    $options = array('accessrules' => 0);

    try {
        $projects = $projectApiConnector->listAll("P*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $projects = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($projects);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $projects = $projectApiConnector->listAll();
    } catch (ResponseException $e) {
        $projects = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($projects);
    echo "</pre>";
}

/* Project
 * \PhpTwinfield\Project
 * Available getters: getBehaviour, getCode, getInUse, getInUseToString, getMessages, getName, getOffice, getOfficeToString, getResult, getShortName, getStatus, getTouched, getType, getTypeToString, getUID, getVatCode, getVatCodeToString, hasMessages, getProjects
 * Available setters: setBehaviour, setBehaviourFromString, setCode, setName, setOffice, setOfficeFromString, setShortName, setStatus, setStatusFromString, setType, setTypeFromString, setVatCode, setVatCodeFromString, setProjects
 */

/* ProjectProjects
 * \PhpTwinfield\ProjectProjects
 * Available getters: getAuthoriser, getAuthoriserInherit, getAuthoriserInheritToString, getAuthoriserLocked, getAuthoriserLockedToString, getAuthoriserToString, getBillable, getBillableForRatio, getBillableForRatioToString, getBillableInherit, getBillableInheritToString,
 * getBillableLocked, getBillableLockedToString, getBillableToString, getCustomer, getCustomerInherit, getCustomerInheritToString, getCustomerLocked, getCustomerLockedToString, getCustomerToString, getInvoiceDescription, getMessages, getRate, getRateInherit, getRateInheritToString,
 * getRateLocked, getRateLockedToString, getRateToString, getResult, getValidFrom, getValidFromToString, getValidTill, getValidTillToString, hasMessages, getQuantities
 *
 * Available setters: setAuthoriser, setAuthoriserFromString, setAuthoriserInherit, setAuthoriserInheritFromString, setAuthoriserLocked, setAuthoriserLockedFromString, setBillable, setBillableForRatio, setBillableForRatioFromString, setBillableFromString, setBillableInherit, setBillableInheritFromString,
 * setBillableLocked, setBillableLockedFromString, setCustomer, setCustomerFromString, setCustomerInherit, setCustomerInheritFromString, setCustomerLocked, setCustomerLockedFromString, setInvoiceDescription, setRate, setRateFromString, setRateInherit, setRateInheritFromString,
 * setRateLocked, setRateLockedFromString, setValidFrom, setValidFromFromString, setValidTill, setValidTillFromString, addQuantity, removeQuantity
 *
 */

/* ProjectQuantity
 * \PhpTwinfield\ProjectQuantity
 * Available getters: getBillable, getBillableLocked, getBillableLockedToString, getBillableToString, getLabel, getMandatory, getMandatoryToString, getMessages, getRate, getRateToString, getResult, hasMessages
 * Available setters: setBillable, setBillableFromString, setBillableLocked, setBillableLockedFromString, setLabel, setMandatory, setMandatoryFromString, setRate, setRateFromString
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($projects as $key => $project) {
        echo "Project {$key}<br />";
        echo "Code: {$project->getCode()}<br />";
        echo "Name: {$project->getName()}<br /><br />";
    }
}

// Read a Project based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $project = $projectApiConnector->get("P0001", $office);
    } catch (ResponseException $e) {
        $project = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($project);
    echo "</pre>";

    echo "Project<br />";
    echo "Behaviour: {$project->getBehaviour()}<br />";                                                                                   		    // Behaviour|null               Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$project->getCode()}<br />";                                                                                   					// string|null                  Dimension code, must be compliant with the mask of the PRJ Dimension type.
    echo "InUse (bool): {$project->getInUse()}<br />";                                                                                   			// bool|null                    Indicates whether the project is used in a transaction or not. Read-only attribute.
    echo "InUse (string): {$project->getInUseToString()}<br />";                                                                                    // string|null

    if ($project->hasMessages()) {                                                                                              					// bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($project->getMessages(), true) . "<br />";                                                  					// Array|null                   (Error) messages.
    }

    echo "Name: {$project->getName()}<br />";                                                                                   					// string|null                  Project description.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($project->getOffice(), true) . "</pre><br />";                      					// Office|null                  Office code.
    echo "Office (string): {$project->getOfficeToString()}<br />";                                                              					// string|null
    echo "Result: {$project->getResult()}<br />";                                                                               					// int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$project->getShortName()}<br />";                                                                         					// string|null                  Short project description.
    echo "Status: {$project->getStatus()}<br />";                                                                               					// Status|null                  Status of the project.
    echo "Touched: {$project->getTouched()}<br />";                                                                                                 // int|null                     Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($project->getType(), true) . "</pre><br />";                                       // DimensionType|null           Dimension type. See Dimension type. Dimension type of cost centers is PRJ.
    echo "Type (string): {$project->getTypeToString()}<br />";                                                                                      // string|null
    echo "UID: {$project->getUID()}<br />";                                                                                                         // string|null                  Unique identification of the dimension. Read-only attribute.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($project->getVatCode(), true) . "</pre><br />";                                       // VatCode|null                 The VAT code if one code will apply for all projects within the project. Note that if any VAT codes are
    echo "VatCode (string): {$project->getVatCodeToString()}<br />";                                                                                // string|null                  defined on project level, these will apply regardless of what is defined on project level.

    $projectProjects = $project->getProjects();                                                                           			                // ProjectProjects|null         ProjectProjects object.

    echo "Authoriser (\\PhpTwinfield\\User): <pre>" . print_r($projectProjects->getAuthoriser(), true) . "</pre><br />";                      		// User|null                    A specific authoriser for a project.
    echo "Authoriser (string): {$projectProjects->getAuthoriserToString()}<br />";                                                              	// string|null                  If "change" = allow then locked = false and inherit = false
    echo "Authoriser Inherit (bool): {$projectProjects->getAuthoriserInherit()}<br />";                                                             // bool|null
    echo "Authoriser Inherit (string): {$projectProjects->getAuthoriserInheritToString()}<br />";                                                   // string|null                  If "change" = disallow then locked = true and inherit = false
    echo "Authoriser Locked (bool): {$projectProjects->getAuthoriserLocked()}<br />";                                                               // bool|null
    echo "Authoriser Locked (string): {$projectProjects->getAuthoriserLockedToString()}<br />";                                                     // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Billable (bool): {$projectProjects->getBillable()}<br />";                                                                                // bool|null                    Choose to make a project billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    echo "Billable (string): {$projectProjects->getBillableToString()}<br />";                                                                      // string|null                  You could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Billable ForRatio (bool): {$projectProjects->getBillableForRatio()}<br />";                                                               // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Billable ForRatio (string): {$projectProjects->getBillableForRatioToString()}<br />";                                                     // string|null                  If "change" = allow then locked = false and inherit = false.
    echo "Billable Inherit (bool): {$projectProjects->getBillableInherit()}<br />";                                                                 // bool|null
    echo "Billable Inherit (string): {$projectProjects->getBillableInheritToString()}<br />";                                                       // string|null                  If "change" = disallow then locked = true and inherit = false.
    echo "Billable Locked (bool): {$projectProjects->getBillableLocked()}<br />";                                                                   // bool|null
    echo "Billable Locked (string): {$projectProjects->getBillableLockedToString()}<br />";                                                         // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Customer (\\PhpTwinfield\\Customer): <pre>" . print_r($projectProjects->getCustomer(), true) . "</pre><br />";                      		// Customer|null                A project always needs to be linked to a customer.
    echo "Customer (string): {$projectProjects->getCustomerToString()}<br />";                                                              	    // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    echo "Customer Inherit (bool): {$projectProjects->getCustomerInherit()}<br />";                                                                 // bool|null
    echo "Customer Inherit (string): {$projectProjects->getCustomerInheritToString()}<br />";                                                       // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Customer Locked (bool): {$projectProjects->getCustomerLocked()}<br />";                                                                   // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Customer Locked (string): {$projectProjects->getCustomerLockedToString()}<br />";                                                         // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Invoice Description: {$projectProjects->getInvoiceDescription()}<br />";                                                                  // string|null                  This field can be used to enter a longer project description which will be available on the invoice template.

    if ($projectProjects->hasMessages()) {                                                                                					        // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($projectProjects->getMessages(), true) . "<br />";                                    					        // Array|null                   (Error) messages.
    }

    echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($projectProjects->getRate(), true) . "</pre><br />";                      		            // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Rate (string): {$projectProjects->getRateToString()}<br />";                                                              	            // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Rate Inherit (bool): {$projectProjects->getRateInherit()}<br />";                                                                         // bool|null
    echo "Rate Inherit (string): {$projectProjects->getRateInheritToString()}<br />";                                                               // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Rate Locked (bool): {$projectProjects->getRateLocked()}<br />";                                                                           // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Rate Locked (string): {$projectProjects->getRateLockedToString()}<br />";                                                                 // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Result: {$projectProjects->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
    echo "Valid From (\\DateTimeInterface): <pre>" . print_r($projectProjects->getValidFrom(), true) . "</pre><br />";                              // \DateTimeInterface|null      A project can be set to only be valid for certain dates. Users will then only be able to book hours to the project during these dates.
    echo "Valid From (string): {$projectProjects->getValidFromToString()}<br />";                                                                   // string|null
    echo "Valid Till (\\DateTimeInterface): <pre>" . print_r($projectProjects->getValidTill(), true) . "</pre><br />";                              // \DateTimeInterface|null      A project can be set to only be valid for certain dates. Users will then only be able to book hours to the project during these dates.
    echo "Valid Till (string): {$projectProjects->getValidTillToString()}<br />";                                                                   // string|null

    $projectQuantities = $projectProjects->getQuantities();                                                                                         // array|null                   Array of ProjectQuantity objects.

    foreach ($projectQuantities as $key => $projectQuantity) {
        echo "ProjectQuantity {$key}<br />";

        echo "Billable (bool): {$projectQuantity->getBillable()}<br />";                                                                            // bool|null                    Is the quantity line billable or not.
        echo "Billable (string): {$projectQuantity->getBillableToString()}<br />";                                                                  // string|null                  If "billable" = true and "change is not allowed" then locked = true
        echo "Billable Locked (bool): {$projectQuantity->getBillableLocked()}<br />";                                                               // bool|null                    If "billable" = true and "change is allowed" then locked = false
        echo "Billable Locked (string): {$projectQuantity->getBillableLockedToString()}<br />";                                                     // string|null

        if ($projectQuantity->hasMessages()) {                                                                                					    // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($projectQuantity->getMessages(), true) . "<br />";                                    					    // Array|null                   (Error) messages.
        }

        echo "Label: {$projectQuantity->getLabel()}<br />";                                                                         			    // string|null                  The label of the quantity.
        echo "Mandatory (bool): {$projectQuantity->getMandatory()}<br />";                                                                          // bool|null                    Is the quantity line mandatory or not.
        echo "Mandatory (string): {$projectQuantity->getMandatoryToString()}<br />";                                                                // string|null
        echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($projectQuantity->getRate(), true) . "</pre><br />";                                    // Rate|null                    The rate.
        echo "Rate (string): {$projectQuantity->getRateToString()}<br />";                                                                          // string|null
        echo "Result: {$projectQuantity->getResult()}<br />";                                                                                       // int|null                     Result (0 = error, 1 or empty = success).
    }
}

// Copy an existing Project to a new entity
if ($executeCopy) {
    try {
        $project = $projectApiConnector->get("P0001", $office);
    } catch (ResponseException $e) {
        $project = $e->getReturnedObject();
    }

    $project->setCode("P0100");

    try {
        $projectCopy = $projectApiConnector->send($project);
    } catch (ResponseException $e) {
        $projectCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($projectCopy);
    echo "</pre>";

    echo "Result of copy process: {$projectCopy->getResult()}<br />";
    echo "Code of copied Project: {$projectCopy->getCode()}<br />";
}

// Create a new Project from scratch, alternatively read an existing Project as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $project = new \PhpTwinfield\Project;

    // Required values for creating a new Project
                                                                                                                                                    //
    $project->setCode('P0100');                                                                                                                     // string|null                  Dimension code, must be compliant with the mask of the PRJ Dimension type.
    $project->setName("Example Project");                                                                                                           // string|null                  Project description.
    $project->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $project->setOfficeFromString($officeCode);                                                                                                     // string|null
    //$project->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                    // Status|null                  For creating and updating status may be left empty. For deleting deleted should be used.
    //$project->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                   // Status|null                  In case a project is in use, its status has been changed into hide. Hidden projects can be activated by using active.
    //$project->setStatusFromString('active');                                                                                                      // string|null
    //$project->setStatusFromString('deleted');                                                                                                     // string|null
    $dimensionType = new \PhpTwinfield\DimensionType;
    $dimensionType->setCode('PRJ');
    $project->setType($dimensionType);                                                                                                              // DimensionType|null           Dimension type. See Dimension type. Dimension type of cost centers is PRJ.
    $project->setTypeFromString('PRJ');                                                                                                             // string|null

    // Optional values for creating a new Project
    $project->setShortName("ExmplPrj");                                                                                                             // string|null                  Short project description.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $project->setVatCode($vatCode);                                                                                                                 // VatCode|null                 The VAT code if one code will apply for all projects within the project. Note that if any VAT codes are
    $project->setVatCodeFromString('VH');                                                                                                           // string|null                  defined on project level, these will apply regardless of what is defined on project level.

    $projectProjects = new \PhpTwinfield\ProjectProjects;

    $authoriser = new \PhpTwinfield\User;
    $authoriser->setCode('TWINAPPS');
    $projectProjects->setAuthoriser($authoriser);                      		                                                                        // User|null                    A specific authoriser for a project.
    $projectProjects->setAuthoriserFromString('TWINAPPS');                                                                                          // string|null                  If "change" = allow then locked = false and inherit = false
    $projectProjects->setAuthoriserInherit(false);                                                                                                  // bool|null
    $projectProjects->setAuthoriserInheritFromString('false');                                                                                      // string|null                  If "change" = disallow then locked = true and inherit = false
    $projectProjects->setAuthoriserLocked(false);                                                                                                   // bool|null
    $projectProjects->setAuthoriserLockedFromString('false');                                                                                       // string|null                  If "change" = inherit then locked = true and inherit = true
    $projectProjects->setBillable(false);                                                                                                           // bool|null                    Choose to make a project billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    $projectProjects->setBillableFromString('false');                                                                                               // string|null                  You could also decide that these settings should be inherited from project or user level (@inherit).
    $projectProjects->setBillableForRatio(false);                                                                                                   // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    $projectProjects->setBillableForRatioFromString('false');                                                                                       // string|null                  If "change" = allow then locked = false and inherit = false.
    $projectProjects->setBillableInherit(false);                                                                                                    // bool|null
    $projectProjects->setBillableInheritFromString('false');                                                                                        // string|null                  If "change" = disallow then locked = true and inherit = false.
    $projectProjects->setBillableLocked(false);                                                                                                     // bool|null
    $projectProjects->setBillableLockedFromString('false');                                                                                         // string|null                  If "change" = inherit then locked = true and inherit = true
    $customer = new \PhpTwinfield\Customer;
    $customer->setCode('1000');
    //$projectProjects->setCustomer($customer);                                                                                                     // Customer|null                A project always needs to be linked to a customer.
    //$projectProjects->setCustomerFromString('1000');                                                                                              // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    $projectProjects->setCustomerInherit(true);                                                                                                     // bool|null
    $projectProjects->setCustomerInheritFromString('true');                                                                                         // string|null                  If "change" = allow then locked = false and inherit = false
    $projectProjects->setCustomerLocked(true);                                                                                                      // bool|null                    If "change" = disallow then locked = true and inherit = false
    $projectProjects->setCustomerLockedFromString('true');                                                                                          // string|null                  If "change" = inherit then locked = true and inherit = true
    $projectProjects->setInvoiceDescription('Example Invoice Description');                                                                         // string|null                  This field can be used to enter a longer project description which will be available on the invoice template.
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('DIRECT');
    $projectProjects->setRate($rate);                      		                                                                                    // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    $projectProjects->setRateFromString('DIRECT');                                                                                                  // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    $projectProjects->setRateInherit(false);                                                                                                        // bool|null
    $projectProjects->setRateInheritFromString('false');                                                                                            // string|null                  If "change" = allow then locked = false and inherit = false
    $projectProjects->setRateLocked(true);                                                                                                          // bool|null                    If "change" = disallow then locked = true and inherit = false
    $projectProjects->setRateLockedFromString('true');                                                                                              // string|null                  If "change" = inherit then locked = true and inherit = true
    $validFrom = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $projectProjects->setValidFrom($validFrom);                                                                                                     // \DateTimeInterface|null      A project can be set to only be valid for certain dates. Users will then only be able to book hours to the project during these dates.
    $projectProjects->setValidFromFromString('20190101');                                                                                           // string|null
    $validTill = \DateTime::createFromFormat('d-m-Y', '31-12-2019');
    $projectProjects->setValidTill($validTill);                                                                                                     // \DateTimeInterface|null      A project can be set to only be valid for certain dates. Users will then only be able to book hours to the project during these dates.
    $projectProjects->setValidTillFromString('20191231');                                                                                           // string|null

    // The minimum amount of ProjectQuantities linked to a ProjectProjects object is 0, the maximum amount is 4
    $projectQuantity = new \PhpTwinfield\ProjectQuantity;

    $projectQuantity->setBillable(false);                                                                                                           // bool|null                    Is the quantity line billable or not.
    $projectQuantity->setBillableFromString('false');                                                                                               // string|null                  If "billable" = true and "change is not allowed" then locked = true
    $projectQuantity->setBillableLocked(false);                                                                                                     // bool|null
    $projectQuantity->setBillableLockedFromString('false');                                                                                         // string|null                  If "billable" = true and "change is allowed" then locked = false
    $projectQuantity->setLabel('Example Quantity');                                                       	                                        // string|null
    $projectQuantity->setMandatory(false);                                                                                                          // bool|null                    Is the quantity line mandatory or not.
    $projectQuantity->setMandatoryFromString('false');                                                                                              // string|null
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('KILOMETERS');
    $projectQuantity->setRate($rate);                      		                                                                                    // Rate|null                    The rate.
    $projectQuantity->setRateFromString('KILOMETERS');                                                       	                                    // string|null

    $projectProjects->addQuantity($projectQuantity);                                                                                                // ProjectQuantity              Add a ProjectQuantity object to the ProjectProjects object
    //$projectProjects->removeQuantity(0);                                                                                                          // int                          Remove a quantity based on the index of the quantity within the array

    $project->setProjects($projectProjects);                                                                                                        // ProjectProjects              Set the ProjectProjects object tot the Project object

    try {
        $projectNew = $projectApiConnector->send($project);
    } catch (ResponseException $e) {
        $projectNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($projectNew);
    echo "</pre>";

    echo "Result of creation process: {$projectNew->getResult()}<br />";
    echo "Code of new Project: {$projectNew->getCode()}<br />";
}

// Delete a Project based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $projectDeleted = $projectApiConnector->delete("P0100", $office);
    } catch (ResponseException $e) {
        $projectDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($projectDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$projectDeleted->getResult()}<br />";
}