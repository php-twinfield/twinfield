<?php

/* Currency
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Settings/Company/Currencies
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
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

/* Currency API Connector
 * \PhpTwinfield\ApiConnectors\CurrencyApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$currencyApiConnector = new \PhpTwinfield\ApiConnectors\CurrencyApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all currencies
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
 *                         Available options:      office
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 */

//List all with pattern "EUR", field 0 (= search code or name), firstRow 1, maxRows 10, options []
if ($executeListAllWithFilter) {
    try {
        $currencies = $currencyApiConnector->listAll("EUR", 0, 1, 10);
    } catch (ResponseException $e) {
        $currencies = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currencies);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $currencies = $currencyApiConnector->listAll();
    } catch (ResponseException $e) {
        $currencies = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currencies);
    echo "</pre>";
}

/* Currency
 * \PhpTwinfield\Currency
 * Available getters: getCode, getMessages, getName, getOffice, getResult, getShortName, getStatus, hasMessages, getRates
 * Available setters: setCode, setName, setOffice, setShortName, setStatus, addRate, removeRate
 */

/* CurrencyRate
 * \PhpTwinfield\CurrencyRate
 * Available getters: getMessages, getRate, getResult, getStartDate, getStatus, hasMessages
 * Available setters: setRate ,setStartDate, setStatus
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($currencies as $key => $currency) {
        echo "Currency {$key}<br />";
        echo "Code: {$currency->getCode()}<br />";
        echo "Name: {$currency->getName()}<br /><br />";
    }
}

// Read a Currency based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $currency = $currencyApiConnector->get("USD", $office);
    } catch (ResponseException $e) {
        $currency = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currency);
    echo "</pre>";

    echo "Currency<br />";
    echo "Code: {$currency->getCode()}<br />";                                                                              // string|null                  The code of the currency.

    if ($currency->hasMessages()) {                                                                                         // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($currency->getMessages(), true) . "<br />";                                             // Array|null                   (Error) messages.
    }

    echo "Name: {$currency->getName()}<br />";                                                                              // string|null                  Name of the currency.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($currency->getOffice(), true) . "</pre><br />";                 // Office|null                  Office of the currency.
    echo "Office (string): " . Util::objectToStr($currency->getOffice()) . "<br />";                                        // string|null
    echo "Result: {$currency->getResult()}<br />";                                                                          // int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$currency->getShortName()}<br />";                                                                    // string|null                  Short name of the currency. NOTE: Because of the "hackish" way a currency is read (because Twinfield does not officially support reading currencies) the get() method will not return the current Short Name
    echo "Status: {$currency->getStatus()}<br />";                                                                          // Status|null                  Status of the currency.

    $currencyRates = $currency->getRates();                                                                                 // Array|null                   Array of CurrencyRate objects.

    foreach ($currencyRates as $key => $currencyRate) {
        echo "CurrencyRate {$key}<br />";

        if ($currencyRate->hasMessages()) {                                                                                 // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($currencyRate->getMessages(), true) . "<br />";                                     // Array|null                   (Error) messages.
        }

        echo "Rate: {$currencyRate->getRate()}<br />";                                                                      // float|null                   Conversion rate to be used as of the start date.
        echo "Result: {$currencyRate->getResult()}<br />";                                                                  // int|null                     Result (0 = error, 1 or empty = success).
        echo "StartDate (\\DateTimeInterface): <pre>" . print_r($currencyRate->getStartDate(), true) . "</pre><br />";      // DateTimeInterface|null       Starting date of the rate.
        echo "StartDate (string): " . Util::formatDate($currencyRate->getStartDate()) . "<br />";                           // string|null
        echo "Status: {$currencyRate->getStatus()}<br />";                                                                  // Status|null                  Status of the currency rate.
    }
}

// Copy an existing Currency to a new entity
if ($executeCopy) {
    try {
        $currency = $currencyApiConnector->get("USD", $office);
    } catch (ResponseException $e) {
        $currency = $e->getReturnedObject();
    }

    $currency->setCode("USD2");

    try {
        $currencyCopy = $currencyApiConnector->send($currency);
    } catch (ResponseException $e) {
        $currencyCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currencyCopy);
    echo "</pre>";

    echo "Result of copy process: {$currencyCopy->getResult()}<br />";
    echo "Code of copied Currency: {$currencyCopy->getCode()}<br />";
}

// Create a new Currency from scratch, alternatively read an existing Currency as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $currency = new \PhpTwinfield\Currency;

    // Required values for creating a new Currency
    $currency->setCode('JPY');                                                                                              // string|null                 The code of the currency.
    $currency->setName("Japanese yen");                                                                                     // string|null                 Name of the currency.
    $currency->setOffice($office);                                                                                          // Office|null                 Office code of the currency.
    $currency->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                      // string|null

    // Optional values for creating a new Currency
    $currency->setShortName("Yen");                                                                                         // string|null                 Short name of the currency.
    //$currency->setShortName("&yen;");                                                                                     // string|null
    $currency->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                             // Status|null                 For creating and updating status may be left empty.
    //$currency->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                          // Status|null                 For deleting deleted should be used. In case a dimension that is used in a transaction is deleted,
                                                                                                                            //                             its status has been changed into hide. Hidden dimensions can be activated by using active.

    // The minimum amount of CurrencyRates linked to a Currency object is 0
    $currencyRate = new \PhpTwinfield\CurrencyRate;
    $currencyRate->setRate(122.87);                                                                                         // float|null                  Conversion rate to be used as of the start date.
    $startDate = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $currencyRate->setStartDate($startDate);                                                                                // DateTimeInterface|null      Starting date of the rate.
    $currencyRate->setStartDate(Util::parseDate('20190101'));                                                               // string|null
    //$currencyRate->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                      // Status|null                 For creating and updating status may be left empty. NOTE: Do not use $currencyRate->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());
                                                                                                                            //                             For deleting deleted should be used.

    $currency->addRate($currencyRate);                                                                                      // CurrencyRate                Add a CurrencyRate object to the Currency object
    //$currency->removeRate(0);                                                                                             // int                         Remove a rate based on the index of the rate within the array

    try {
        $currencyNew = $currencyApiConnector->send($currency);
    } catch (ResponseException $e) {
        $currencyNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currencyNew);
    echo "</pre>";

    echo "Result of creation process: {$currencyNew->getResult()}<br />";
    echo "Code of new Currency: {$currencyNew->getCode()}<br />";
}

// Delete a Currency based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $currencyDeleted = $currencyApiConnector->delete("JPY", $office);
    } catch (ResponseException $e) {
        $currencyDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($currencyDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$currencyDeleted->getResult()}<br />";
}