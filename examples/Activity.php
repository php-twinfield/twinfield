<?php

/* Activity
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Projects/Manage
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

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
 * Available getters: getBehaviour, getCode, getInUse, getInUseToString, getMessages, getName, getOffice, getOfficeToString, getResult, getShortName, getStatus, getTouched, getType, getTypeToString, getUID, getVatCode, getVatCodeToString, hasMessages, getProjects
 * Available setters: setBehaviour, setBehaviourFromString, setCode, setName, setOffice, setOfficeFromString, setShortName, setStatus, setStatusFromString, setType, setTypeFromString, setVatCode, setVatCodeFromString, setProjects
 */

/* ActivityProjects
 * \PhpTwinfield\ActivityProjects
 * Available getters: getAuthoriser, getAuthoriserInherit, getAuthoriserInheritToString, getAuthoriserLocked, getAuthoriserLockedToString, getAuthoriserToString, getBillable, getBillableForRatio, getBillableForRatioToString, getBillableInherit, getBillableInheritToString,
 * getBillableLocked, getBillableLockedToString, getBillableToString, getCustomer, getCustomerInherit, getCustomerInheritToString, getCustomerLocked, getCustomerLockedToString, getCustomerToString, getInvoiceDescription, getMessages, getRate, getRateInherit, getRateInheritToString,
 * getRateLocked, getRateLockedToString, getRateToString, getResult, getValidFrom, getValidFromToString, getValidTill, getValidTillToString, hasMessages, getQuantities
 *
 * Available setters: setAuthoriser, setAuthoriserFromString, setAuthoriserInherit, setAuthoriserInheritFromString, setAuthoriserLocked, setAuthoriserLockedFromString, setBillable, setBillableForRatio, setBillableForRatioFromString, setBillableFromString, setBillableInherit, setBillableInheritFromString,
 * setBillableLocked, setBillableLockedFromString, setCustomer, setCustomerFromString, setCustomerInherit, setCustomerInheritFromString, setCustomerLocked, setCustomerLockedFromString, setInvoiceDescription, setRate, setRateFromString, setRateInherit, setRateInheritFromString,
 * setRateLocked, setRateLockedFromString, setValidFrom, setValidFromFromString, setValidTill, setValidTillFromString, addQuantity, removeQuantity
 *
 */

/* ActivityQuantity
 * \PhpTwinfield\ActivityQuantity
 * Available getters: getBillable, getBillableLocked, getBillableLockedToString, getBillableToString, getLabel, getMandatory, getMandatoryToString, getMessages, getRate, getRateToString, getResult, hasMessages
 * Available setters: setBillable, setBillableFromString, setBillableLocked, setBillableLockedFromString, setLabel, setMandatory, setMandatoryFromString, setRate, setRateFromString
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
    echo "InUse (string): {$activity->getInUseToString()}<br />";                                                                                   // string|null

    if ($activity->hasMessages()) {                                                                                              					// bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($activity->getMessages(), true) . "<br />";                                                  					// Array|null                   (Error) messages.
    }

    echo "Name: {$activity->getName()}<br />";                                                                                   					// string|null                  Activity description.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($activity->getOffice(), true) . "</pre><br />";                      					// Office|null                  Office code.
    echo "Office (string): {$activity->getOfficeToString()}<br />";                                                              					// string|null
    echo "Result: {$activity->getResult()}<br />";                                                                               					// int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$activity->getShortName()}<br />";                                                                         					// string|null                  Short activity description.
    echo "Status: {$activity->getStatus()}<br />";                                                                               					// Status|null                  Status of the activity.
    echo "Touched: {$activity->getTouched()}<br />";                                                                                                // int|null                     Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($activity->getType(), true) . "</pre><br />";                                      // DimensionType|null           Dimension type. See Dimension type. Dimension type of activities is ACT.
    echo "Type (string): {$activity->getTypeToString()}<br />";                                                                                     // string|null
    echo "UID: {$activity->getUID()}<br />";                                                                                                        // string|null                  Unique identification of the dimension. Read-only attribute.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($activity->getVatCode(), true) . "</pre><br />";                                      // VatCode|null                 The VAT code if one code will apply for all activities within the project. Note that if any VAT codes are
    echo "VatCode (string): {$activity->getVatCodeToString()}<br />";                                                                               // string|null                  defined on activity level, these will apply regardless of what is defined on project level.

    $activityProjects = $activity->getProjects();                                                                           			            // ActivityProjects|null        ActivityProjects object.

    echo "Authoriser (\\PhpTwinfield\\User): <pre>" . print_r($activityProjects->getAuthoriser(), true) . "</pre><br />";                      		// User|null                    A specific authoriser for an activity.
    echo "Authoriser (string): {$activityProjects->getAuthoriserToString()}<br />";                                                              	// string|null                  If "change" = allow then locked = false and inherit = false
    echo "Authoriser Inherit (bool): {$activityProjects->getAuthoriserInherit()}<br />";                                                            // bool|null
    echo "Authoriser Inherit (string): {$activityProjects->getAuthoriserInheritToString()}<br />";                                                  // string|null                  If "change" = disallow then locked = true and inherit = false
    echo "Authoriser Locked (bool): {$activityProjects->getAuthoriserLocked()}<br />";                                                              // bool|null
    echo "Authoriser Locked (string): {$activityProjects->getAuthoriserLockedToString()}<br />";                                                    // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Billable (bool): {$activityProjects->getBillable()}<br />";                                                                               // bool|null                    Choose to make an activity billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    echo "Billable (string): {$activityProjects->getBillableToString()}<br />";                                                                     // string|null                  You could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Billable ForRatio (bool): {$activityProjects->getBillableForRatio()}<br />";                                                              // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Billable ForRatio (string): {$activityProjects->getBillableForRatioToString()}<br />";                                                    // string|null                  If "change" = allow then locked = false and inherit = false.
    echo "Billable Inherit (bool): {$activityProjects->getBillableInherit()}<br />";                                                                // bool|null
    echo "Billable Inherit (string): {$activityProjects->getBillableInheritToString()}<br />";                                                      // string|null                  If "change" = disallow then locked = true and inherit = false.
    echo "Billable Locked (bool): {$activityProjects->getBillableLocked()}<br />";                                                                  // bool|null
    echo "Billable Locked (string): {$activityProjects->getBillableLockedToString()}<br />";                                                        // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Customer (\\PhpTwinfield\\Customer): <pre>" . print_r($activityProjects->getCustomer(), true) . "</pre><br />";                      		// Customer|null                An activity always needs to be linked to a customer.
    echo "Customer (string): {$activityProjects->getCustomerToString()}<br />";                                                              	    // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    echo "Customer Inherit (bool): {$activityProjects->getCustomerInherit()}<br />";                                                                // bool|null
    echo "Customer Inherit (string): {$activityProjects->getCustomerInheritToString()}<br />";                                                      // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Customer Locked (bool): {$activityProjects->getCustomerLocked()}<br />";                                                                  // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Customer Locked (string): {$activityProjects->getCustomerLockedToString()}<br />";                                                        // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Invoice Description: {$activityProjects->getInvoiceDescription()}<br />";                                                                 // string|null                  This field can be used to enter a longer activity description which will be available on the invoice template.

    if ($activityProjects->hasMessages()) {                                                                                					        // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($activityProjects->getMessages(), true) . "<br />";                                    					        // Array|null                   (Error) messages.
    }

    echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($activityProjects->getRate(), true) . "</pre><br />";                      		            // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    echo "Rate (string): {$activityProjects->getRateToString()}<br />";                                                              	            // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    echo "Rate Inherit (bool): {$activityProjects->getRateInherit()}<br />";                                                                        // bool|null
    echo "Rate Inherit (string): {$activityProjects->getRateInheritToString()}<br />";                                                              // string|null                  If "change" = allow then locked = false and inherit = false
    echo "Rate Locked (bool): {$activityProjects->getRateLocked()}<br />";                                                                          // bool|null                    If "change" = disallow then locked = true and inherit = false
    echo "Rate Locked (string): {$activityProjects->getRateLockedToString()}<br />";                                                                // string|null                  If "change" = inherit then locked = true and inherit = true
    echo "Result: {$activityProjects->getResult()}<br />";                                                                                          // int|null                     Result (0 = error, 1 or empty = success).
    echo "Valid From (\\DateTimeInterface): <pre>" . print_r($activityProjects->getValidFrom(), true) . "</pre><br />";                             // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    echo "Valid From (string): {$activityProjects->getValidFromToString()}<br />";                                                                  // string|null
    echo "Valid Till (\\DateTimeInterface): <pre>" . print_r($activityProjects->getValidTill(), true) . "</pre><br />";                             // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    echo "Valid Till (string): {$activityProjects->getValidTillToString()}<br />";                                                                  // string|null

    $activityQuantities = $activityProjects->getQuantities();                                                                                       // array|null                   Array of ActivityQuantity objects.

    foreach ($activityQuantities as $key => $activityQuantity) {
        echo "ActivityQuantity {$key}<br />";

        echo "Billable (bool): {$activityQuantity->getBillable()}<br />";                                                                           // bool|null                    Is the quantity line billable or not.
        echo "Billable (string): {$activityQuantity->getBillableToString()}<br />";                                                                 // string|null                  If "billable" = true and "change is not allowed" then locked = true
        echo "Billable Locked (bool): {$activityQuantity->getBillableLocked()}<br />";                                                              // bool|null                    If "billable" = true and "change is allowed" then locked = false
        echo "Billable Locked (string): {$activityQuantity->getBillableLockedToString()}<br />";                                                    // string|null

        if ($activityQuantity->hasMessages()) {                                                                                					    // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($activityQuantity->getMessages(), true) . "<br />";                                    					    // Array|null                   (Error) messages.
        }

        echo "Label: {$activityQuantity->getLabel()}<br />";                                                                         			    // string|null                  The label of the quantity.
        echo "Mandatory (bool): {$activityQuantity->getMandatory()}<br />";                                                                         // bool|null                    Is the quantity line mandatory or not.
        echo "Mandatory (string): {$activityQuantity->getMandatoryToString()}<br />";                                                               // string|null
        echo "Rate (\\PhpTwinfield\\Rate): <pre>" . print_r($activityQuantity->getRate(), true) . "</pre><br />";                                   // Rate|null                    The rate.
        echo "Rate (string): {$activityQuantity->getRateToString()}<br />";                                                                         // string|null
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
    $activity->setOfficeFromString($officeCode);                                                                                                    // string|null

    // Optional values for creating a new Activity
    $activity->setShortName("ExmplAct");                                                                                                            // string|null                  Short activity description.
    //$activity->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                   // Status|null                  For creating and updating status may be left empty. For deleting deleted should be used.
    //$activity->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                  // Status|null                  In case an activity is in use, its status has been changed into hide. Hidden activities can be activated by using active.
    //$activity->setStatusFromString('active');                                                                                                     // string|null
    //$activity->setStatusFromString('deleted');                                                                                                    // string|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $activity->setVatCode($vatCode);                                                                                                                // VatCode|null                 The VAT code if one code will apply for all activities within the project. Note that if any VAT codes are
    $activity->setVatCodeFromString('VH');                                                                                                          // string|null                  defined on activity level, these will apply regardless of what is defined on project level.

    $activityProjects = new \PhpTwinfield\ActivityProjects;

    $authoriser = new \PhpTwinfield\User;
    $authoriser->setCode('TWINAPPS');
    $activityProjects->setAuthoriser($authoriser);                      		                                                                    // User|null                    A specific authoriser for an activity.
    $activityProjects->setAuthoriserFromString('TWINAPPS');                                                                                         // string|null                  If "change" = allow then locked = false and inherit = false
    $activityProjects->setAuthoriserInherit(false);                                                                                                 // bool|null
    $activityProjects->setAuthoriserInheritFromString('false');                                                                                     // string|null                  If "change" = disallow then locked = true and inherit = false
    $activityProjects->setAuthoriserLocked(false);                                                                                                  // bool|null
    $activityProjects->setAuthoriserLockedFromString('false');                                                                                      // string|null                  If "change" = inherit then locked = true and inherit = true
    $activityProjects->setBillable(false);                                                                                                          // bool|null                    Choose to make an activity billable (true) or not (false) and whether or not it should be included when calculating the "productivity" ratio (@forratio).
    $activityProjects->setBillableFromString('false');                                                                                              // string|null                  You could also decide that these settings should be inherited from project or user level (@inherit).
    $activityProjects->setBillableForRatio(false);                                                                                                  // bool|null                    You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
    $activityProjects->setBillableForRatioFromString('false');                                                                                      // string|null                  If "change" = allow then locked = false and inherit = false.
    $activityProjects->setBillableInherit(false);                                                                                                   // bool|null
    $activityProjects->setBillableInheritFromString('false');                                                                                       // string|null                  If "change" = disallow then locked = true and inherit = false.
    $activityProjects->setBillableLocked(false);                                                                                                    // bool|null
    $activityProjects->setBillableLockedFromString('false');                                                                                        // string|null                  If "change" = inherit then locked = true and inherit = true
    $customer = new \PhpTwinfield\Customer;
    $customer->setCode('1000');
    //$activityProjects->setCustomer($customer);                                                                                                    // Customer|null                An activity always needs to be linked to a customer.
    //$activityProjects->setCustomerFromString('1000');                                                                                             // string|null                  Choose to have the customer ‘inherited’ (from a project) or you can specify the customer here.
    $activityProjects->setCustomerInherit(true);                                                                                                    // bool|null
    $activityProjects->setCustomerInheritFromString('true');                                                                                        // string|null                  If "change" = allow then locked = false and inherit = false
    $activityProjects->setCustomerLocked(true);                                                                                                     // bool|null                    If "change" = disallow then locked = true and inherit = false
    $activityProjects->setCustomerLockedFromString('true');                                                                                         // string|null                  If "change" = inherit then locked = true and inherit = true
    $activityProjects->setInvoiceDescription('Example Invoice Description');                                                                        // string|null                  This field can be used to enter a longer activity description which will be available on the invoice template.
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('DIRECT');
    $activityProjects->setRate($rate);                      		                                                                                // Rate|null                    Choose to define a specific rate code here or you could also decide that these settings should be inherited from project or user level (@inherit).
    $activityProjects->setRateFromString('DIRECT');                                                                                                 // string|null                  You can also set whether a change of the rate code is allowed or disallowed when a user is entering their timesheet (@locked).
    $activityProjects->setRateInherit(false);                                                                                                       // bool|null
    $activityProjects->setRateInheritFromString('false');                                                                                           // string|null                  If "change" = allow then locked = false and inherit = false
    $activityProjects->setRateLocked(true);                                                                                                         // bool|null                    If "change" = disallow then locked = true and inherit = false
    $activityProjects->setRateLockedFromString('true');                                                                                             // string|null                  If "change" = inherit then locked = true and inherit = true
    $validFrom = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $activityProjects->setValidFrom($validFrom);                                                                                                    // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    $activityProjects->setValidFromFromString('20190101');                                                                                          // string|null
    $validTill = \DateTime::createFromFormat('d-m-Y', '31-12-2019');
    $activityProjects->setValidTill($validTill);                                                                                                    // DateTimeInterface|null       An activity can be set to only be valid for certain dates. Users will then only be able to book hours to the activity during these dates.
    $activityProjects->setValidTillFromString('20191231');                                                                                          // string|null

    // The minimum amount of ActivityQuantities linked to a ActivityProjects object is 0, the maximum amount is 4
    $activityQuantity = new \PhpTwinfield\ActivityQuantity;
    $activityQuantity->setBillable(false);                                                                                                          // bool|null                    Is the quantity line billable or not.
    $activityQuantity->setBillableFromString('false');                                                                                              // string|null                  If "billable" = true and "change is not allowed" then locked = true
    $activityQuantity->setBillableLocked(false);                                                                                                    // bool|null
    $activityQuantity->setBillableLockedFromString('false');                                                                                        // string|null                  If "billable" = true and "change is allowed" then locked = false
    $activityQuantity->setLabel('Example Quantity');                                                       	                                        // string|null
    $activityQuantity->setMandatory(false);                                                                                                         // bool|null                    Is the quantity line mandatory or not.
    $activityQuantity->setMandatoryFromString('false');                                                                                             // string|null
    $rate = new \PhpTwinfield\Rate;
    $rate->setCode('KILOMETERS');
    $activityQuantity->setRate($rate);                      		                                                                                // Rate|null                    The rate.
    $activityQuantity->setRateFromString('KILOMETERS');                                                       	                                    // string|null

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

// Delete a Activity based off the passed in code and optionally the office.
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