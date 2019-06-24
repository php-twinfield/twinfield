<?php

/* CostCenter
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Dimensions/CostCenters
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/CostCenters
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

/* CostCenter API Connector
 * \PhpTwinfield\ApiConnectors\CostCenterApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$costCenterApiConnector = new \PhpTwinfield\ApiConnectors\CostCenterApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all cost centers
 * @param string $pattern  The search pattern. May contain wildcards * and ?
 * @param int    $field    The search field determines which field or fields will be searched. The available fields
 *                         depends on the finder type. Passing a value outside the specified values will cause an
 *                         error.
 * @param int    $firstRow First row to return, useful for paging
 * @param int    $maxRows  Maximum number of rows to return, useful for paging
 * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
 *                         to add multiple options. An option name may be used once, specifying an option multiple
 *                         times will cause an error.
 *
 *                         Available options:      office, level, modifiedsince, group
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         level                   Specifies the dimension level.
 *                         Available values:       1, 2, 3, 4, 5, 6
 *                         Usage:                  $options['level'] = 2;
 *
 *                         modifiedsince           Restricts to dimensions modified after or at the specified date (and time).
 *                         Available values:       yyyyMMddHHmmss or yyyyMMdd
 *                         Usage:                  $options['modifiedsince'] = '20190101100000'; or $options['level'] = '20190101';
 *
 *                         group                   Specifies the dimension group. May contain wildcards * and ?
 *                         Usage:                  $options['group'] = 'DimensionGroup';
 */

//List all with pattern "Apeldoorn", field 0 (= search code or name), firstRow 1, maxRows 10, options -> modifiedsince = '20190101100000', group = 'DimensionGroup'
if ($executeListAllWithFilter) {
    $options = array('modifiedsince' => '20190101100000', 'group' => 'DimensionGroup');

    try {
        $costCenters = $costCenterApiConnector->listAll("Apeldoorn", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $costCenters = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenters);
    echo "</pre>";
}


//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $costCenters = $costCenterApiConnector->listAll();
    } catch (ResponseException $e) {
        $costCenters = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenters);
    echo "</pre>";
}

/* CostCenter
 * \PhpTwinfield\CostCenter
 * Available getters: getBehaviour, getCode, getInUse, getMessages, getName, getOffice, getResult, getShortName, getStatus, getTouched, getType, getUID, hasMessages
 * Available setters: fromCode, setBehaviour, setCode, setName, setOffice, setShortName, setStatus, setType
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($costCenters as $key => $costCenter) {
        echo "CostCenter {$key}<br />";
        echo "Code: {$costCenter->getCode()}<br />";
        echo "Name: {$costCenter->getName()}<br /><br />";
    }
}

// Read a CostCenter based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $costCenter = $costCenterApiConnector->get("00000", $office);
    } catch (ResponseException $e) {
        $costCenter = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenter);
    echo "</pre>";

    echo "CostCenter<br />";
    echo "Behaviour: {$costCenter->getBehaviour()}<br />";                                                          // Behaviour|null       Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$costCenter->getCode()}<br />";                                                                    // string|null          Dimension code, must be compliant with the mask of the KPL Dimension type.
    echo "InUse (bool): {$costCenter->getInUse()}<br />";                                                           // bool|null            Indicates whether the cost center is used in a financial transaction or not. Read-only attribute.
    echo "InUse (string): " . Util::formatBoolean($costCenter->getInUse()) . "<br />";                              // string|null

    if ($costCenter->hasMessages()) {                                                                               // bool                 Object contains (error) messages true/false.
        echo "Messages: " . print_r($costCenter->getMessages(), true) . "<br />";                                   // Array|null           (Error) messages.
    }

    echo "Name: {$costCenter->getName()}<br />";                                                                    // string|null          Name of the dimension.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($costCenter->getOffice(), true) . "</pre><br />";       // Office|null          Office.
    echo "Office (string): " . Util::objectToStr($costCenter->getOffice()) . "<br />";                              // string|null
    echo "Result: {$costCenter->getResult()}<br />";                                                                // int|null             Result (0 = error, 1 or empty = success).
    echo "ShortName: {$costCenter->getShortName()}<br />";                                                          // string|null          Not in use.
    echo "Status: {$costCenter->getStatus()}<br />";                                                                // Status|null          Status of the cost center.
    echo "Touched: {$costCenter->getTouched()}<br />";                                                              // int|null             Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($costCenter->getType(), true) . "</pre><br />";    // DimensionType|null   Dimension type. See Dimension type. Dimension type of cost centers is KPL.
    echo "Type (string): " . Util::objectToStr($costCenter->getType()) . "<br />";                                  // string|null
    echo "UID: {$costCenter->getUID()}<br />";                                                                      // string|null          Unique identification of the dimension. Read-only attribute.
}

// Copy an existing CostCenter to a new entity
if ($executeCopy) {
    try {
        $costCenter = $costCenterApiConnector->get("00000", $office);
    } catch (ResponseException $e) {
        $costCenter = $e->getReturnedObject();
    }

    $costCenter->setCode(null);                                                                                     // string|null          Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$costCenter->setCode("00010");                                                                                // string|null          Dimension code, must be compliant with the mask of the KPL Dimension type.

    try {
        $costCenterCopy = $costCenterApiConnector->send($costCenter);
    } catch (ResponseException $e) {
        $costCenterCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenterCopy);
    echo "</pre>";

    echo "Result of copy process: {$costCenterCopy->getResult()}<br />";
    echo "Code of copied CostCenter: {$costCenterCopy->getCode()}<br />";
}

// Create a new CostCenter from scratch, alternatively read an existing CostCenter as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $costCenter = new \PhpTwinfield\CostCenter;

    // Required values for creating a new CostCenter
    $costCenter->setName("CostCenterName");                                                                         // string|null          Name of the dimension.
    $costCenter->setCode(null);                                                                                     // string|null          Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$costCenter->setCode('00020');                                                                                // string|null          Dimension code, must be compliant with the mask of the KPL Dimension type.
    $costCenter->setOffice($office);                                                                                // Office|null          Office.
    $costCenter->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                            // string|null

    // Optional values for creating a new CostCenter
    $costCenter->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                   // Status|null          For creating and updating status may be left empty.
    //$costCenter->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                // Status|null          For deleting deleted should be used. In case a dimension that is used in a transaction is deleted,
                                                                                                                    //                      its status has been changed into hide. Hidden dimensions can be activated by using active.
    try {
        $costCenterNew = $costCenterApiConnector->send($costCenter);
    } catch (ResponseException $e) {
        $costCenterNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenterNew);
    echo "</pre>";

    echo "Result of creation process: {$costCenterNew->getResult()}<br />";
    echo "Code of new CostCenter: {$costCenterNew->getCode()}<br />";
}

// Delete a CostCenter based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $costCenterDeleted = $costCenterApiConnector->delete("00020", $office);
    } catch (ResponseException $e) {
        $costCenterDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($costCenterDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$costCenterDeleted->getResult()}<br />";
}