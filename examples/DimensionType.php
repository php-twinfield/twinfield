<?php
/* DimensionType
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Settings/Company/DimensionTypes
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* DimensionType API Connector
 * \PhpTwinfield\ApiConnectors\DimensionTypeApiConnector
 * Available methods: get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeModify                      = false;

$dimensionTypeApiConnector = new \PhpTwinfield\ApiConnectors\DimensionTypeApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all dimension types
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
 *                         Available options:      office, dim, finlevel
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         dim                     Restricts to the type of the specified dimension.
 *                         Usage:                  $options['dim'] = '1000';
 *
 *                         finlevel                Specifies the financial level.
 *                         Available values:       1, 2, 3, 4, 5, 6
 *                         Usage:                  $options['finlevel'] = '2';
 *
 */

//List all with pattern "*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> finlevel = 2
if ($executeListAllWithFilter) {
    $options = array('finlevel' => 2);

    try {
        $dimensionTypes = $dimensionTypeApiConnector->listAll("*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $dimensionTypes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($dimensionTypes);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $dimensionTypes = $dimensionTypeApiConnector->listAll();
    } catch (ResponseException $e) {
        $dimensionTypes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($dimensionTypes);
    echo "</pre>";
}

/* DimensionType
 * \PhpTwinfield\DimensionType
 * Available getters: getCode, getMask, getMessages, getName, getOffice, getResult, getShortName, getStatus, hasMessages, getAddress, getLevels
 * Available setters: fromCode, setCode, setMask, setName, setOffice, setShortName, setStatus, setAddress, setLevels
 */

/* DimensionTypeLevels
 * \PhpTwinfield\DimensionTypeLevels
 * Available getters: getFinancials, getMessages, getResult, getTime, hasMessages
 * Available setters: setFinancials, setTime
 */

/* DimensionTypeAddress
 * \PhpTwinfield\DimensionTypeAddress
 * Available getters: getLabel1, getLabel2, getLabel3, getLabel4, getLabel5, getLabel6, getMessages, getResult, hasMessages
 * Available setters: setLabel1, setLabel2, setLabel3, setLabel4, setLabel5, setLabel6
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($dimensionTypes as $key => $dimensionType) {
        echo "DimensionType {$key}<br />";
        echo "Code: {$dimensionType->getCode()}<br />";
        echo "Name: {$dimensionType->getName()}<br /><br />";
    }
}

// Read a DimensionType based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $dimensionType = $dimensionTypeApiConnector->get("ACT", $office);
    } catch (ResponseException $e) {
        $dimensionType = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($dimensionType);
    echo "</pre>";

    echo "DimensionType<br />";
    echo "Code: {$dimensionType->getCode()}<br />";                                                                                                     // string|null                  Dimension type.
    echo "Mask: {$dimensionType->getMask()}<br />";                                                                                                     // string|null                  Dimension type mask.

    if ($dimensionType->hasMessages()) {                                                                                                                // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($dimensionType->getMessages(), true) . "<br />";                                                                    // Array|null                   (Error) messages.
    }

    echo "Name: {$dimensionType->getName()}<br />";                                                                                                     // string|null                  Dimension type name.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($dimensionType->getOffice(), true) . "</pre><br />";                                        // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($dimensionType->getOffice()) . "<br />";                                                               // string|null
    echo "Result: {$dimensionType->getResult()}<br />";                                                                                                 // int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$dimensionType->getShortName()}<br />";                                                                                           // string|null                  Dimension type short name.
    echo "Status: {$dimensionType->getStatus()}<br />";                                                                                                 // Status|null                  Status of the dimension type.

    $dimensionTypeLevels = $dimensionType->getLevels();                                                                                                 // DimensionTypeLevels|null     DimensionTypeLevels object.

    echo "Financials: {$dimensionTypeLevels->getFinancials()}<br />";                                                                                   // int|null                     Read-only attribute. Financial level.

    if ($dimensionTypeLevels->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($dimensionTypeLevels->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
    }

    echo "Result: {$dimensionTypeLevels->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
    echo "Time: {$dimensionTypeLevels->getTime()}<br />";                                                                                               // int|null                     Read-only attribute. Level in time & expenses.

    $dimensionTypeAddress = $dimensionType->getAddress();                                                                                               // DimensionTypeAddress|null    DimensionTypeAddress object.

    echo "Label 1: {$dimensionTypeAddress->getLabel1()}<br />";                                                                                         // string|null                  Address labels, description of field1 in the dimension address element.
    echo "Label 2: {$dimensionTypeAddress->getLabel2()}<br />";                                                                                         // string|null                  Address labels, description of field2 in the dimension address element.
    echo "Label 3: {$dimensionTypeAddress->getLabel3()}<br />";                                                                                         // string|null                  Address labels, description of field3 in the dimension address element.
    echo "Label 4: {$dimensionTypeAddress->getLabel4()}<br />";                                                                                         // string|null                  Address labels, description of field4 in the dimension address element.
    echo "Label 5: {$dimensionTypeAddress->getLabel5()}<br />";                                                                                         // string|null                  Address labels, description of field5 in the dimension address element.
    echo "Label 6: {$dimensionTypeAddress->getLabel6()}<br />";                                                                                         // string|null                  Address labels, description of field6 in the dimension address element.

    if ($dimensionTypeAddress->hasMessages()) {                                                                                                         // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($dimensionTypeAddress->getMessages(), true) . "<br />";                                                             // Array|null                   (Error) messages.
    }

    echo "Result: {$dimensionTypeAddress->getResult()}<br />";                                                                                          // int|null                     Result (0 = error, 1 or empty = success).
}

// Copy an existing DimensionType to a new entity
if ($executeModify) {
    try {
        $dimensionType = $dimensionTypeApiConnector->get("CRD", $office);
    } catch (ResponseException $e) {
        $dimensionType = $e->getReturnedObject();
    }

    $dimensionType->setMask("2[0-9][0-9][0-9]");                                                                                                        // string|null                    Dimension type mask.
    $dimensionType->setName("Creditors");                                                                                                               // string|null                    Dimension type name.
    $dimensionType->setShortName("Cred");                                                                                                               // string|null                    Dimension type short name.

    $dimensionTypeAddress = $dimensionType->getAddress();

    $dimensionTypeAddress->setLabel1('Tav');                                                                                                            // string|null                    Address labels, description of field1 in the dimension address element.
    $dimensionTypeAddress->setLabel2('Address');                                                                                                        // string|null                    Address labels, description of field2 in the dimension address element.
    $dimensionTypeAddress->setLabel4('VAT');                                                                                                            // string|null                    Address labels, description of field4 in the dimension address element.
    $dimensionTypeAddress->setLabel5('CoC');                                                                                                            // string|null                    Address labels, description of field5 in the dimension address element.

    try {
        $dimensionTypeModify = $dimensionTypeApiConnector->send($dimensionType);
    } catch (ResponseException $e) {
        $dimensionTypeModify = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($dimensionTypeModify);
    echo "</pre>";

    echo "Result of modify process: {$dimensionTypeModify->getResult()}<br />";
    echo "Code of modified DimensionType: {$dimensionTypeModify->getCode()}<br />";
    echo "Status of modified DimensionType: {$dimensionTypeModify->getStatus()}<br />";
}
