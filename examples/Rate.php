<?php

/* Rate
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Projects/TimeAndExpenses/Rates
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Rate API Connector
 * \PhpTwinfield\ApiConnectors\RateApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$rateApiConnector = new \PhpTwinfield\ApiConnectors\RateApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all rates
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
 *
 *                         ratetype                Specifies the rate type.
 *                         Available values:       time, quantity
 *                         Usage:                  $options['ratetype'] = 'quantity';
 */

//List all with pattern "DIR*", field 0 (= search code or number), firstRow 1, maxRows 10, options -> ratetype = 'quantity'
if ($executeListAllWithFilter) {
    $options = array('ratetype' => 'time');

    try {
        $rates = $rateApiConnector->listAll("DIR*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $rates = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rates);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $rates = $rateApiConnector->listAll();
    } catch (ResponseException $e) {
        $rates = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rates);
    echo "</pre>";
}

/* Rate
 * \PhpTwinfield\Rate
 * Available getters: getCode, getCreated, getCreatedToString, getCurrency, getCurrencyToString, getMessages, getModified, getModifiedToString, getName, getOffice, getOfficeToString, getResult, getShortName, getStatus, getTouched, getType, getUnit, getUser, getUserToString, hasMessages, getRateChanges
 * Available setters: setCode, setCurrency, setCurrencyFromString, setName, setOffice, setOfficeFromString, setShortName, setStatus, setStatusFromString, setType, setTypeFromString, setUnit, addRateChange, removeRateChange
 */

/* RateRateChange
 * \PhpTwinfield\RateRateChange
 * Available getters: getBeginDate, getBeginDateToString, getEndDate, getEndDateToString, getExternalRate, getID, getInternalRate, getMessages, getResult, getStatus, hasMessages
 * Available setters: setBeginDate, setBeginDateFromString, setEndDate, setEndDateFromString, setExternalRate, setID, setInternalRate, setStatus, setStatusFromString
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($rates as $key => $rate) {
        echo "Rate {$key}<br />";
        echo "Code: {$rate->getCode()}<br />";
        echo "Name: {$rate->getName()}<br /><br />";
    }
}

// Read a Rate based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $rate = $rateApiConnector->get("DIRECT", $office);
    } catch (ResponseException $e) {
        $rate = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rate);
    echo "</pre>";

    echo "Rate<br />";
    echo "Code: {$rate->getCode()}<br />";                                                                              	// string|null                  Rate code.
    echo "Created (\\DateTimeInterface): <pre>" . print_r($rate->getCreated(), true) . "</pre><br />";              	    // \DateTimeInterface|null      The date/time the rate was created. Read-only attribute.
    echo "Created (string): {$rate->getCreatedToString()}<br />";                                                   	    // string|null
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($rate->getCurrency(), true) . "</pre><br />";           	// Currency|null                Currency code.
    echo "Currency (string): {$rate->getCurrencyToString()}<br />";                                                     	// string|null

    if ($rate->hasMessages()) {                                                                                         	// bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($rate->getMessages(), true) . "<br />";                                             	// Array|null                   (Error) messages.
    }

    echo "Modified (\\DateTimeInterface): <pre>" . print_r($rate->getModified(), true) . "</pre><br />";            	    // \DateTimeInterface|null      The date/time the rate was modified. Read-only attribute.
    echo "Modified (string): {$rate->getModifiedToString()}<br />";                                                 	    // string|null
    echo "Name: {$rate->getName()}<br />";                                                                              	// string|null                  Rate description.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($rate->getOffice(), true) . "</pre><br />";                 	// Office|null                  Office code.
    echo "Office (string): {$rate->getOfficeToString()}<br />";                                                         	// string|null
    echo "Result: {$rate->getResult()}<br />";                                                                          	// int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$rate->getShortName()}<br />";                                                                    	// string|null                  Short rate description.
    echo "Status: {$rate->getStatus()}<br />";                                                                          	// Status|null                  Status of the rate.
    echo "Touched: {$rate->getTouched()}<br />";                                                                        	// int|null                     The number of times the rate is modified. Read-only attribute.
    echo "Type: {$rate->getType()}<br />";                                                                              	// RateType|null                The rate type.
    echo "Unit: {$rate->getUnit()}<br />";                                                                              	// int|null                     How will be charged e.g. if charged per hour Time, set it to 60. If charged per 8 hours, set it to 8 * 60 = 460.
                                                                                                                        	//                              Quantities refers to items such as kilometers. If charged per kilometer set it to 1.
    echo "User (\\PhpTwinfield\\User): <pre>" . print_r($rate->getUser(), true) . "</pre><br />";                       	// User|null                    The code of the user who created or modified the Rate. Read-only attribute.
    echo "User (string): {$rate->getUserToString()}<br />";                                                             	// string|null

    $rateRateChanges = $rate->getRateChanges();                                                                         	// Array|null                   Array of RateRateChange objects.

    foreach ($rateRateChanges as $key => $rateRateChange) {
        echo "RateRateChange {$key}<br />";

        echo "BeginDate (\\DateTimeInterface): <pre>" . print_r($rateRateChange->getBeginDate(), true) . "</pre><br />";    // \DateTimeInterface|null      Begin date of the rate.
        echo "BeginDate (string): {$rateRateChange->getBeginDateToString()}<br />";                                         // string|null
        echo "EndDate (\\DateTimeInterface): <pre>" . print_r($rateRateChange->getEndDate(), true) . "</pre><br />";        // \DateTimeInterface|null      End date of the rate.
        echo "EndDate (string): {$rateRateChange->getEndDateToString()}<br />";                                             // string|null
        echo "ExternalRate: {$rateRateChange->getExternalRate()}<br />";                                                    // int|null                     The external rate e.g. the selling price per unit.
        echo "ID: {$rateRateChange->getID()}<br />";                                                                        // int|null                     Line ID.
        echo "InternalRate: {$rateRateChange->getInternalRate()}<br />";                                                    // int|null                     The internal rate e.g. the cost price per unit.

        if ($rateRateChange->hasMessages()) {                                                                               // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($rateRateChange->getMessages(), true) . "<br />";                                   // Array|null                   (Error) messages.
        }

        echo "Result: {$rateRateChange->getResult()}<br />";                                                                // int|null                     Result (0 = error, 1 or empty = success).
        echo "Status: {$rateRateChange->getStatus()}<br />";                                                                // Status|null                  Status of the rate change.
    }
}

// Copy an existing Rate to a new entity
if ($executeCopy) {
    try {
        $rate = $rateApiConnector->get("DIRECT", $office);
    } catch (ResponseException $e) {
        $rate = $e->getReturnedObject();
    }

    $rate->setCode("DIRECT2");

    try {
        $rateCopy = $rateApiConnector->send($rate);
    } catch (ResponseException $e) {
        $rateCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rateCopy);
    echo "</pre>";

    echo "Result of copy process: {$rateCopy->getResult()}<br />";
    echo "Code of copied Rate: {$rateCopy->getCode()}<br />";
}

// Create a new Rate from scratch, alternatively read an existing Rate as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $rate = new \PhpTwinfield\Rate;

    // Required values for creating a new Rate
    $rate->setCode('DIRECT2');                                                                                // string|null                    Rate code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $rate->setCurrency($currency);                                                                            // Currency|null                  Currency code.
    $rate->setCurrencyFromString('EUR');                                                                      // string|null
    $rate->setName("Example Rate");                                                                           // string|null                    Rate description.
    $rate->setOffice($office);                                                                                // Office|null                    Office code.
    $rate->setOfficeFromString($officeCode);                                                                  // string|null
    $rate->setType(\PhpTwinfield\Enums\RateType::TIME());                                                  	  // RateType|null                  The rate type.
    $rate->setTypeFromString('time');                                                                     	  // string|null
    $rate->setUnit(60);                                                                                       // int|null                       How will be charged e.g. if charged per hour Time, set it to 60. If charged per 8 hours, set it to 8 * 60 = 460.
                                                                                                              //                                Quantities refers to items such as kilometers. If charged per kilometer set it to 1.
    // Optional values for creating a new Rate
    $rate->setShortName("ExmpleRate");                                                                        // string|null                    Short rate description.
    $rate->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                   // Status|null                    For creating and updating status may be left empty.
    //$rate->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                //                                For deleting deleted should be used. In case a rate that is in use, its status has been changed into hide.
                                                                                                              //                                Hidden rates can be activated by using active.
    $rate->setStatusFromString('active');                                                                     // string|null
    //$rate->setStatusFromString('deleted');                                                                  // string|null

    // The minimum amount of RateRateChanges linked to a Rate object is 0
    $rateRateChange = new \PhpTwinfield\RateRateChange;
    $beginDate = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $rateRateChange->setBeginDate($beginDate);                                                                // \DateTimeInterface|null        Begin date of the rate.
    $rateRateChange->setBeginDateFromString('20190101');                                                      // string|null
    $endDate = \DateTime::createFromFormat('d-m-Y', '31-12-2019');
    $rateRateChange->setEndDate($endDate);                                                                    // \DateTimeInterface|null        Begin date of the rate.
    $rateRateChange->setEndDateFromString('20191231');                                                        // string|null
    $rateRateChange->setExternalRate(60);                                                                     // float|null                     The internal rate e.g. the cost price per unit.
    $rateRateChange->setID(2);                                                                                // int|null                       Line ID.
    $rateRateChange->setInternalRate(120);                                                                    // float|null                     The internal rate e.g. the cost price per unit.
    //$rateRateChange->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                      // Status|null                    Status of the rate line. For creating and updating status may be left empty. NOTE: Do not use $rateRateChange->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());
                                                                                                              //                                For deleting deleted should be used.
    //$rateRateChange->setStatusFromString('deleted');                                                        // string|null                    NOTE: Do not use $rateRateChange->setStatusFromString('active');

    $rate->addRateChange($rateRateChange);                                                                    // RateRateChange                 Add a RateRateChange object to the Rate object
    //$rate->removeRateChange(2);                                                                             // int                            Remove a rate change based on the id of the rate change

    try {
        $rateNew = $rateApiConnector->send($rate);
    } catch (ResponseException $e) {
        $rateNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rateNew);
    echo "</pre>";

    echo "Result of creation process: {$rateNew->getResult()}<br />";
    echo "Code of new Rate: {$rateNew->getCode()}<br />";
    /* */
}

// Delete a Rate based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $rateDeleted = $rateApiConnector->delete("DIRECT2", $office);
    } catch (ResponseException $e) {
        $rateDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($rateDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$rateDeleted->getResult()}<br />";
}