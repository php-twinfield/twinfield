<?php

/* Activity
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Projects/Manage
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Activity API Connector
 * \PhpTwinfield\ApiConnectors\ActivityApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$activityApiConnector = new \PhpTwinfield\ApiConnectors\ActivityApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all activities
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
 *                         trscode                 Specifies the transaction code and will only return the projects or activities which are related to the specified transaction code.
 *                         Usage:                  $options['trscode'] = '20190001';
 *
 */

//List all with pattern "A*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> accessrules = 0
if ($executeListAllWithFilter) {
    $options = array('accessrules' => 0);

    try {
        $activities = $activityApiConnector->listAll("A*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $activities = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activities);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $activities = $activityApiConnector->listAll();
    } catch (ResponseException $e) {
        $activities = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activities);
    echo "</pre>";
}

/* Activity
 * \PhpTwinfield\Activity
 * Available getters: getBehaviour, getCode, getInUse, getMessages, getName, getOffice, getResult, getShortName, getStatus, getTouched, getType, getUID, getVatCode, hasMessages, getProjects
 * Available setters: setBehaviour, setCode, setName, setOffice, setShortName, setStatus, setType, setVatCode, setProjects
 */

/* ActivityProjects
 * \PhpTwinfield\ActivityProjects
 * Available getters: getAuthoriser, getAuthoriserInherit, getAuthoriserLocked, getBillable, getBillableForRatio, getBillableInherit, getBillableLocked, getCustomer, getCustomerInherit, getCustomerLocked, getInvoiceDescription, getMessages, getRate, getRateInherit,
 * getRateLocked, getResult, getValidFrom, getValidTill, hasMessages, getQuantities
 *
 * Available setters: setAuthoriser, setAuthoriserInherit, setAuthoriserLocked, setBillable, setBillableForRatio, setBillableInherit, setBillableLocked, setCustomer, setCustomerInherit, setCustomerLocked, setInvoiceDescription, setRate, setRateInherit,
 * setRateLocked, setValidFrom, setValidTill, addQuantity, removeQuantity
 *
 */

/* ActivityQuantity
 * \PhpTwinfield\ActivityQuantity
 * Available getters: getBillable, getBillableLocked, getLabel, getMandatory, getMessages, getRate, getResult, hasMessages
 * Available setters: setBillable, setBillableLocked, setLabel, setMandatory, setRate
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($activities as $key => $activity) {
        echo "Activity {$key}<br />";
        echo "Code: {$activity->getCode()}<br />";
        echo "Name: {$activity->getName()}<br /><br />";
    }
}

// Read an Activity based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $activity = $activityApiConnector->get("A000", $office);
    } catch (ResponseException $e) {
        $activity = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activity);
    echo "</pre>";

    echo "Activity<br />";
    echo "Behaviour: {$activity->getBehaviour()}<br />";                                                                                   		    // Behaviour|null               Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$activity->getCode()}<br />";                                                                                   					// string|null                  Dimension code, must be compliant with the mask of the ACT Dimension type.
    echo "InUse (bool): {$activity->getInUse()}<br />";                                                                                   			// bool|null                    Indicates whether the activity is used in a transaction or not. Read-only attribute.
    echo "InUse (string): " . Util::formatBoolean($activity->getInUse()) . "<br />";                                                                // string|null

    if ($activity->hasMessages()) {                                                                                              					// bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($activity->getMessages(), true) . "<br />";                                                  					// Array|null                   (Error) messages.
    }

    echo "Name: {$activity->getName()}<br />";                                                                                   					// string|null                  Activity description.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($activity->getOffice(), true) . "</pre><br />";                      					// Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($activity->getOffice()) . "<br />";                                                              	// string|null
    echo "Result: {$activity->getResult()}<br />";                                                                               					// int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$activity->getShortName()}<br />";                                                                         					// string|null                  Short activity description.
    echo "Status: {$activity->getStatus()}<br />";                                                                               					// Status|null                  Status of the activity.
    echo "Touched: {$activity->getTouched()}<br />";                                                                                                // int|null                     Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($activity->getType(), true) . "</pre><br />";                                      // DimensionType|null           Dimension type. See Dimension type. Dimension type of activities is ACT.
    echo "Type (string): " . Util::objectToStr($activity->getType()) . "<br />";                                                                    // string|null
    echo "UID: {$activity->getUID()}<br />";                                                                                                        // string|null                  Unique identification of the dimension. Read-only attribute.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($activity->getVatCode(), true) . "</pre><br />";                                      // VatCode|null                 The VAT code if one code will apply for all activities within the project. Note that if any VAT codes are
    echo "VatCode (string): " . Util::objectToStr($activity->getVatCode()) . "<br />";                                                              // string|null                  defined on activity level, these will apply regardless of what is defined on project level.

    $activityProjects = $activity->getProjects();                                                                           			            // ActivityProjects|null        ActivityProjects object.

    echo "Authoriser (\\PhpTwinfield\\User): <pre>" . print_r($activityProjects->getAuthoriser(), true) . "</pre><br />";                      		// User|null                    A specific authoriser for an activity.
    echo "Authoriser (string): " . Util::objectToStr($activityProjects->getAuthoriser()) . "<br />";                                                // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Authoriser Inherit (bool): {$activityProjects->getAuthoriserInherit()}<br />";                                                            // bool|null
    echo "Authoriser Inherit (string): " . Util::formatBoolean($activityProjects->getAuthoriserInherit()) . "<br />";                               // string|null                  If "change" = disallow then locked = true and inherit = false
    echo "Authoriser Locked (bool): {$activityProjects->getAuthoriserLocked()}<br />";                                                              // bool|null
    echo "Authoriser Locked (string): " . Util::formatBoolean($activityProjects->getAuthoriserLocked()) . "<br />";                                 // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Billable (bool): {$activityProjects->getBillable()}<br />";                                                                               // bool|null                    Choose to make an activity billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    echo "Billable (string): " . Util::formatBoolean($activityProjects->getBillable()) . "<br />";                                                  // string|null                  You could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Billable ForRatio (bool): {$activityProjects->getBillableForRatio()}<br />";                                                              // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Billable ForRatio (string): " . Util::formatBoolean($activityProjects->getBillableForRatio()) . "<br />";                                 // string|null                  If "change" = allow then locked = false and inherit = false.
    echo "Billable Inherit (bool): {$activityProjects->getBillableInherit()}<br />";                                                                // bool|null
    echo "Billable Inherit (string): " . Util::formatBoolean($activityProjects->getBillableInherit()) . "<br />";                                   // string|null                  If "change" = disallow then locked = true and inherit = false.
    echo "Billable Locked (bool): {$activityProjects->getBillableLocked()}<br />";                                                                  // bool|null
    echo "Billable Locked (string): " . Util::formatBoolean($activityProjects->getBillableLocked()) . "<br />";                                     // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Customer (\\PhpTwinfield\\Customer): <pre>" . print_r($activityProjects->getCustomer(), true) . "</pre><br />";                      		// Customer|null                An activity always needs to be linked to a customer.
    echo "Customer (string): " . Util::objectToStr($activityProjects->getCustomer()) . "<br />";                                                    // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    echo "Customer Inherit (bool): {$activityProjects->getCustomerInherit()}<br />";                                                                // bool|null
    echo "Customer Inherit (string): " . Util::formatBoolean($activityProjects->getCustomerInherit()) . "<br />";                                   // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Customer Locked (bool): {$activityProjects->getCustomerLocked()}<br />";                                                                  // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Customer Locked (string): " . Util::formatBoolean($activityProjects->getCustomerLocked()) . "<br />";                                     // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Invoice Description: {$activityProjects->getInvoiceDescription()}<br />";                                                                 // string|null                  This field can be used to enter a longer activity description which will be available on the invoice template.

    if ($activityProjects->hasMessages()) {                                                                                					        // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($activityProjects->getMessages(), true) . "<br />";                                    					        // Array|null                   (Error) messages.
    }

    echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($activityProjects->getRate(), true) . "</pre><br />";                      		            // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Rate (string): " . Util::objectToStr($activityProjects->getRate()) . "<br />";                                                            // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Rate Inherit (bool): {$activityProjects->getRateInherit()}<br />";                                                                        // bool|null
    echo "Rate Inherit (string): " . Util::formatBoolean($activityProjects->getRateInherit()) . "<br />";                                           // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Rate Locked (bool): {$activityProjects->getRateLocked()}<br />";                                                                          // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Rate Locked (string): " . Util::formatBoolean($activityProjects->getRateLocked()) . "<br />";                                             // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Result: {$activityProjects->getResult()}<br />";                                                                                          // int|null                     Result (0 = error, 1 or empty = success).
    echo "Valid From (\\DateTimeInterface): <pre>" . print_r($activityProjects->getValidFrom(), true) . "</pre><br />";                             // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    echo "Valid From (string): " . Util::formatDate($activityProjects->getValidFrom()) . "<br />";                                                  // string|null
    echo "Valid Till (\\DateTimeInterface): <pre>" . print_r($activityProjects->getValidTill(), true) . "</pre><br />";                             // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    echo "Valid Till (string): " . Util::formatDate($activityProjects->getValidTill()) . "<br />";                                                  // string|null

    $activityQuantities = $activityProjects->getQuantities();                                                                                       // array|null                   Array of ActivityQuantity objects.

    foreach ($activityQuantities as $key => $activityQuantity) {
        echo "ActivityQuantity {$key}<br />";

        echo "Billable (bool): {$activityQuantity->getBillable()}<br />";                                                                           // bool|null                    Is the quantity line billable or not.
        echo "Billable (string): " . Util::formatBoolean($activityQuantity->getBillable()) . "<br />";                                              // string|null                  If "billable" = true and "change is not allowed" then locked = true
        echo "Billable Locked (bool): {$activityQuantity->getBillableLocked()}<br />";                                                              // bool|null                    If "billable" = true and "change is allowed" then locked = false
        echo "Billable Locked (string): " . Util::formatBoolean($activityQuantity->getBillableLocked()) . "<br />";                                 // string|null

        if ($activityQuantity->hasMessages()) {                                                                                					    // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($activityQuantity->getMessages(), true) . "<br />";                                    					    // Array|null                   (Error) messages.
        }

        echo "Label: {$activityQuantity->getLabel()}<br />";                                                                         			    // string|null                  The label of the quantity.
        echo "Mandatory (bool): {$activityQuantity->getMandatory()}<br />";                                                                         // bool|null                    Is the quantity line mandatory or not.
        echo "Mandatory (string): " . Util::formatBoolean($activityQuantity->getMandatory()) . "<br />";                                            // string|null
        echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($activityQuantity->getRate(), true) . "</pre><br />";                                   // Rate|null                    The rate.
        echo "Rate (string): " . Util::objectToStr($activityQuantity->getRate()) . "<br />";                                                        // string|null
        echo "Result: {$activityQuantity->getResult()}<br />";                                                                                      // int|null                     Result (0 = error, 1 or empty = success).
    }
}

// Copy an existing Activity to a new entity
if ($executeCopy) {
    try {
        $activity = $activityApiConnector->get("A000", $office);
    } catch (ResponseException $e) {
        $activity = $e->getReturnedObject();
    }

    $activity->setCode(null);                                                                                                                       // string|null                  Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$activity->setCode('A100');                                                                                                                   // string|null                  Dimension code, must be compliant with the mask of the ACT Dimension type.

    try {
        $activityCopy = $activityApiConnector->send($activity);
    } catch (ResponseException $e) {
        $activityCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activityCopy);
    echo "</pre>";

    echo "Result of copy process: {$activityCopy->getResult()}<br />";
    echo "Code of copied Activity: {$activityCopy->getCode()}<br />";
}

// Create a new Activity from scratch, alternatively read an existing Activity as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $activity = new \PhpTwinfield\Activity;

    // Required values for creating a new Activity
    $activity->setCode(null);                                                                                                                       // string|null                  Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$activity->setCode('A100');                                                                                                                   // string|null                  Dimension code, must be compliant with the mask of the ACT Dimension type.
    $activity->setName("Example Activity");                                                                                                         // string|null                  Activity description.
    $activity->setOffice($office);                                                                                                                  // Office|null                  Office code.
    $activity->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                              // string|null

    // Optional values for creating a new Activity
    $activity->setShortName("ExmplAct");                                                                                                            // string|null                  Short activity description.
    //$activity->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                   // Status|null                  For creating and updating status may be left empty. For deleting deleted should be used.
    //$activity->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                  // Status|null                  In case an activity is in use, its status has been changed into hide. Hidden activities can be activated by using active.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $activity->setVatCode($vatCode);                                                                                                                // VatCode|null                 The VAT code if one code will apply for all activities within the project. Note that if any VAT codes are
    $activity->setVatCode(\PhpTwinfield\VatCode::fromCode('VH'));                                                                                   // string|null                  defined on activity level, these will apply regardless of what is defined on project level.

    $activityProjects = new \PhpTwinfield\ActivityProjects;

    $authoriser = new \PhpTwinfield\User;
    $authoriser->setCode('TWINAPPS');
    $activityProjects->setAuthoriser($authoriser);                      		                                                                    // User|null                    A specific authoriser for an activity.
    $activityProjects->setAuthoriser(\PhpTwinfield\User::fromCode('TWINAPPS'));                                                                     // string|null                  If "change" = allow then locked = false and inherit = false
    $activityProjects->setAuthoriserInherit(false);                                                                                                 // bool|null
    $activityProjects->setAuthoriserLocked(false);                                                                                                  // bool|null
    $activityProjects->setBillable(false);                                                                                                          // bool|null                    Choose to make an activity billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    $activityProjects->setBillableForRatio(false);                                                                                                  // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    $activityProjects->setBillableInherit(false);                                                                                                   // bool|null
    $activityProjects->setBillableLocked(false);                                                                                                    // bool|null
    $customer = new \PhpTwinfield\Customer;
    $customer->setCode('1000');
    //$activityProjects->setCustomer($customer);                                                                                                    // Customer|null                An activity always needs to be linked to a customer.
    //$activityProjects->setCustomer(\PhpTwinfield\Customer::fromCode('1000'));                                                                     // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    $activityProjects->setCustomerInherit(true);                                                                                                    // bool|null
    $activityProjects->setCustomerLocked(true);                                                                                                     // bool|null                    If "change" = disallow then locked = true and inherit = false
    $activityProjects->setInvoiceDescription('Example Invoice Description');                                                                        // string|null                  This field can be used to enter a longer activity description which will be available on the invoice template.
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('DIRECT');
    $activityProjects->setRate($rate);                      		                                                                                // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    $activityProjects->setRate(\PhpTwinfield\Rate::fromCode('DIRECT'));                                                                             // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    $activityProjects->setRateInherit(false);                                                                                                       // bool|null
    $activityProjects->setRateLocked(true);                                                                                                         // bool|null                    If "change" = disallow then locked = true and inherit = false
    $validFrom = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $activityProjects->setValidFrom($validFrom);                                                                                                    // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    $activityProjects->setValidFrom(Util::parseDate('20190101'));                                                                                   // string|null
    $validTill = \DateTime::createFromFormat('d-m-Y', '31-12-2019');
    $activityProjects->setValidTill($validTill);                                                                                                    // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    $activityProjects->setValidTill(Util::parseDate('20191231'));                                                                                   // string|null

    // The minimum amount of ActivityQuantities linked to a ActivityProjects object is 0, the maximum amount is 4
    $activityQuantity = new \PhpTwinfield\ActivityQuantity;
    $activityQuantity->setBillable(false);                                                                                                          // bool|null                    Is the quantity line billable or not.
    $activityQuantity->setBillableLocked(false);                                                                                                    // bool|null
    $activityQuantity->setLabel('Example Quantity');                                                       	                                        // string|null
    $activityQuantity->setMandatory(false);                                                                                                         // bool|null                    Is the quantity line mandatory or not.
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('KILOMETERS');
    $activityQuantity->setRate($rate);                      		                                                                                // Rate|null                    The rate.
    $activityQuantity->setRate(\PhpTwinfield\Rate::fromCode('KILOMETERS'));                                                       	                // string|null

    $activityProjects->addQuantity($activityQuantity);                                                                                              // ActivityQuantity             Add an ActivityQuantity object to the ActivityProjects object
    //$activityProjects->removeQuantity(0);                                                                                                         // int                          Remove a quantity based on the index of the quantity within the array

    $activity->setProjects($activityProjects);                                                                                                      // ActivityProjects             Set the ActivityProjects object tot the Activity object

    try {
        $activityNew = $activityApiConnector->send($activity);
    } catch (ResponseException $e) {
        $activityNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activityNew);
    echo "</pre>";

    echo "Result of creation process: {$activityNew->getResult()}<br />";
    echo "Code of new Activity: {$activityNew->getCode()}<br />";
}

// Delete an Activity based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $activityDeleted = $activityApiConnector->delete("A100", $office);
    } catch (ResponseException $e) {
        $activityDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($activityDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$activityDeleted->getResult()}<br />";
}