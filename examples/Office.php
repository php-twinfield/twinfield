<?php

/* Office
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Companies/All
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices
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

/* Office API Connector
 * \PhpTwinfield\ApiConnectors\OfficeApiConnector
 * Available methods: get, listAll, listAllWithoutOfficeCode
 */

// Run all or only some of the following examples
$executeListAllWithoutOfficeCode    = false;
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;

$officeApiConnector = new \PhpTwinfield\ApiConnectors\OfficeApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$defaultOffice = \PhpTwinfield\Office::fromCode($officeCode);

/* List all offices
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
 *                         Available options:      office, consolidate, withouttaxgroup
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         consolidate             Shows offices for which the consolidate option is true of false.
 *                         Available values:       0 = false, 1 = true
 *                         Usage:                  $options['consolidate'] = 0;
 *
 *                         withouttaxgroup         If set to 1, the list of offices is limited to offices that are not part of a tax group and those offices don't contain VAT returns which are not approved yet.
 *                         Available values:       0 = false, 1 = true
 *                         Usage:                  $options['consolidate'] = 0;
 */

 //List all offices without knowing any of the office codes, only available when using OAuth 2 login
 if ($executeListAllWithoutOfficeCode) {
    try {
        $offices = $officeApiConnector->listAllWithoutOfficeCode();
    } catch (ResponseException $e) {
        $offices = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($offices);
    echo "</pre>";
}

//List all with pattern "NL*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> consolidate = 0
if ($executeListAllWithFilter) {
    $options = array('consolidate' => 0);

    try {
        $offices = $officeApiConnector->listAll("NL*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $offices = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($offices);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $offices = $officeApiConnector->listAll();
    } catch (ResponseException $e) {
        $offices = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($offices);
    echo "</pre>";
}

/* Office
 * \PhpTwinfield\Office
 * Available getters: __toString, getBaseCurrency, getCode, getCountryCode, getCreated, getMessages, getModified, getName, getReportingCurrency, getResult, getShortName, getStatus, getTouched, getUser, getVatFirstQuarterStartsIn, getVatPeriod, hasMessages
 * Available setters: fromCode, setBaseCurrency, setCode, setCountryCode, setName, setReportingCurrency, setShortName, setStatus, setVatFirstQuarterStartsIn,setVatPeriod
 */

if ($executeListAllWithoutOfficeCode || $executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($offices as $key => $office) {
        echo "Office {$key}<br />";
        echo "Code: {$office->getCode()}<br />";
        echo "Name: {$office->getName()}<br /><br />";
        echo "CountryCode (\\PhpTwinfield\\Country): <pre>" . print_r($office->getCountryCode(), true) . "</pre><br />";
        echo "CountryCode (string): " . Util::objectToStr($office->getCountryCode()) . "<br />";
        echo "VatFirstQuarterStartsIn: {$office->getVatFirstQuarterStartsIn()}<br />";
        echo "VatPeriod: {$office->getVatPeriod()}<br />";
    }
}

// Read an Office based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $office = $officeApiConnector->get("NLA000001", $defaultOffice);
    } catch (ResponseException $e) {
        $office = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($office);
    echo "</pre>";

    echo "Office<br />";
    echo "BaseCurrency (\\PhpTwinfield\\Currency): <pre>" . print_r($office->getBaseCurrency(), true) . "</pre><br />";                 // Currency|null                The base currency
    echo "BaseCurrency (string): " . Util::objectToStr($office->getBaseCurrency()) . "<br />";                                          // string|null
    echo "Code: {$office}<br />";                                                                                                       // string|null
    echo "Code: {$office->getCode()}<br />";                                                                                            // string|null
    //echo "CountryCode (\\PhpTwinfield\\Country): <pre>" . print_r($office->getCountryCode(), true) . "</pre><br />";                  // Country|null
    //echo "CountryCode (string): " . Util::objectToStr($office->getCountryCode()) . "<br />";                                          // string|null
    echo "Created (\\DateTimeInterface): <pre>" . print_r($office->getCreated(), true) . "</pre><br />";                                // DateTimeInterface|null       The date/time the office was created.
    echo "Created (string): " . Util::formatDateTime($office->getCreated()) . "<br />";                                                 // string|null

    if ($office->hasMessages()) {                                                                                                       // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($office->getMessages(), true) . "<br />";                                                           // Array|null                   (Error) messages.
    }

    echo "Modified (\\DateTimeInterface): <pre>" . print_r($office->getModified(), true) . "</pre><br />";                              // DateTimeInterface|null       The date/time the office was modified.
    echo "Modified (string): " . Util::formatDateTime($office->getModified()) . "<br />";                                               // string|null
    echo "Name: {$office->getName()}<br />";                                                                                            // string|null
    echo "ReportingCurrency (\\PhpTwinfield\\Currency): <pre>" . print_r($office->getReportingCurrency(), true) . "</pre><br />";       // Currency|null                The reporting currency
    echo "ReportingCurrency (string): " . Util::objectToStr($office->getReportingCurrency()) . "<br />";                                // string|null
    echo "Result: {$office->getResult()}<br />";                                                                                        // int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$office->getShortName()}<br />";                                                                                  // string|null
    echo "Status: {$office->getStatus()}<br />";                                                                                        // Status|null
    echo "Touched: {$office->getTouched()}<br />";                                                                                      // int|null                     The number of times the office is modified.
    echo "User (\\PhpTwinfield\\User): <pre>" . print_r($office->getUser(), true) . "</pre><br />";                                     // User|null                    The code of the user who created or modified the Office.
    echo "User (string): " . Util::objectToStr($office->getUser()) . "<br />";                                                          // string|null
    //echo "VatFirstQuarterStartsIn: {$office->getVatFirstQuarterStartsIn()}<br />";                                                    // string|null
    //echo "VatPeriod: {$office->getVatPeriod()}<br />";                                                                                // string|null
}