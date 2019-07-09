<?php
/* VatCode
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Settings/Company/Vat
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
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

/* VatCode API Connector
 * \PhpTwinfield\ApiConnectors\VatCodeApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$vatCodeApiConnector = new \PhpTwinfield\ApiConnectors\VatCodeApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all VAT codes
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
 *                         Available options:      office, vattype
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         vattype                 Specifies the VAT type.
 *                         Available values:       purchase, sales
 *                         Usage:                  $options['vattype'] = 'purchase';
 *
 */

//List all with pattern "V*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> vattype = 'sales'
if ($executeListAllWithFilter) {
    $options = array('vattype' => 'sales');

    try {
        $vatCodes = $vatCodeApiConnector->listAll("V*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $vatCodes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCodes);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $vatCodes = $vatCodeApiConnector->listAll();
    } catch (ResponseException $e) {
        $vatCodes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCodes);
    echo "</pre>";
}

/* VatCode
 * \PhpTwinfield\VatCode
 * Available getters: getCode, getCreated, getMessages, getModified, getName, getOffice, getResult, getShortName, getStatus, getTouched, getType, getUID, getUser, hasMessages, getPercentages
 * Available setters: fromCode, setCode, setName, setOffice, setShortName, setStatus, setType, addPercentage, removePercentage
 */

/* VatCodePercentage
 * \PhpTwinfield\VatCodePercentage
 * Available getters: getCreated, getDate, getInUse, getMessages, getName, getPercentage, getResult, getShortName, getStatus, getUser, hasMessages, getAccounts
 * Available setters: setDate, setName, setPercentage, setShortName, setStatus, addAccount, removeAccount
 */

/* VatCodeAccount
 * \PhpTwinfield\VatCodeAccount
 * Available getters: getDim1, getGroup, getGroupCountry, getID, getLineType, getMessages, getPercentage, getResult, hasMessages
 * Available setters: setDim1, setGroup, setGroupCountry, setID, setLineType, setPercentage
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($vatCodes as $key => $vatCode) {
        echo "VatCode {$key}<br />";
        echo "Code: {$vatCode->getCode()}<br />";
        echo "Name: {$vatCode->getName()}<br /><br />";
    }
}

// Read a VatCode based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $vatCode = $vatCodeApiConnector->get("VH", $office);
    } catch (ResponseException $e) {
        $vatCode = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCode);
    echo "</pre>";

    echo "VatCode<br />";
    echo "Code: {$vatCode->getCode()}<br />";                                                                                                       // string|null                  VAT code.
    echo "Created (\\DateTimeInterface): <pre>" . print_r($vatCode->getCreated(), true) . "</pre><br />";                                           // \DateTimeInterface|null      The date/time the VAT code was created. Read-only attribute.
    echo "Created (string): " . Util::formatDate($vatCode->getCreated()) . "<br />";                                                                // string|null

    if ($vatCode->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($vatCode->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "Modified (\\DateTimeInterface): <pre>" . print_r($vatCode->getModified(), true) . "</pre><br />";                                         // \DateTimeInterface|null          The most recent date/time the VAT code was modified. Read-only attribute.
    echo "Modified (string): " . Util::formatDate($vatCode->getModified()) . "<br />";                                                              // string|null
    echo "Name: {$vatCode->getName()}<br />";                                                                                                       // string|null                  Name of the VAT.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($vatCode->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($vatCode->getOffice()) . "<br />";                                                                 // string|null
    echo "Result: {$vatCode->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$vatCode->getShortName()}<br />";                                                                                             // string|null                  Short name of the VAT.
    echo "Status: {$vatCode->getStatus()}<br />";                                                                                                   // Status|null                  Status of the VAT.
    echo "Touched: {$vatCode->getTouched()}<br />";                                                                                                 // int|null                     The number of times the VAT code is modified. Read-only attribute.
    echo "Type: {$vatCode->getType()}<br />";                                                                                                       // VatType|null                 The VAT type.
    echo "UID: {$vatCode->getUID()}<br />";                                                                                                         // string|null                  Unique identification of the VAT code. Read-only attribute.
    echo "User (\\PhpTwinfield\\User): <pre>" . print_r($vatCode->getUser(), true) . "</pre><br />";                                                // User|null                    The code of the user who created or modified the VAT code. Read-only attribute.
    echo "User (string): " . Util::objectToStr($vatCode->getUser()) . "<br />";                                                                     // string|null

    $vatCodePercentages = $vatCode->getPercentages();                                                                                               // Array|null                   Array of VatCodePercentage objects.

    foreach ($vatCodePercentages as $key => $vatCodePercentage) {
        echo "VatCodePercentage {$key}<br />";

        if ($vatCodePercentage->hasMessages()) {                                                                                                    // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($vatCodePercentage->getMessages(), true) . "<br />";                                                        // Array|null                   (Error) messages.
        }

        echo "Created (\\DateTimeInterface): <pre>" . print_r($vatCodePercentage->getCreated(), true) . "</pre><br />";                             // \DateTimeInterface|null      The date/time the VAT line was created. Read-only attribute.
        echo "Created (string): " . Util::formatDate($vatCodePercentage->getCreated()) . "<br />";                                                  // string|null
        echo "Date (\\DateTimeInterface): <pre>" . print_r($vatCodePercentage->getDate(), true) . "</pre><br />";                                   // \DateTimeInterface|null      Effective date.
        echo "Date (string): " . Util::formatDate($vatCodePercentage->getDate()) . "<br />";                                                        // string|null
        echo "InUse (bool): <pre>" . print_r($vatCodePercentage->getInUse(), true) . "</pre><br />";                                                // bool|null
        echo "InUse (string): " . Util::formatBoolean($vatCodePercentage->getInUse() . "<br />";                                                    // string|null
        echo "Name: {$vatCodePercentage->getName()}<br />";                                                                                         // string|null                  Name of the VAT line.
        echo "Percentage: {$vatCodePercentage->getPercentage()}<br />";                                                                             // float|null                   Percentage of the VAT line.
        echo "Result: {$vatCodePercentage->getResult()}<br />";                                                                                     // int|null                     Result (0 = error, 1 or empty = success).
        echo "ShortName: {$vatCodePercentage->getShortName()}<br />";                                                                               // string|null                  Short name of the VAT line.
        echo "Status: {$vatCodePercentage->getStatus()}<br />";                                                                                     // Status|null                  Status of the percentage.
        echo "User (\\PhpTwinfield\\User): <pre>" . print_r($vatCodePercentage->getUser(), true) . "</pre><br />";                                  // User|null                    The code of the user who created or modified the VAT line. Read-only attribute.
        echo "User (string): " . Util::objectToStr($vatCodePercentage->getUser()) . "<br />";                                                       // string|null

        $vatCodeAccounts = $vatCodePercentage->getAccounts();                                                                                       // Array|null                   Array of VatCodeAccount objects.

        foreach ($vatCodeAccounts as $key => $vatCodeAccount) {
            echo "VatCodeAccount {$key}<br />";

            if ($vatCodeAccount->hasMessages()) {                                                                                                   // bool                         Object contains (error) messages true/false.
                echo "Messages: " . print_r($vatCodeAccount->getMessages(), true) . "<br />";                                                       // Array|null                   (Error) messages.
            }

            echo "Dim1 (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($vatCodeAccount->getDim1(), true) . "</pre><br />";                        // GeneralLedger|null            General ledger account on which the VAT amount will be posted.
            echo "Dim1 (string): " . Util::objectToStr($vatCodeAccount->getDim1()) . "<br />";                                                      // string|null
            echo "Group (\\PhpTwinfield\\VatGroup): <pre>" . print_r($vatCodeAccount->getGroup(), true) . "</pre><br />";                           // VatGroup|null                 The VAT group.
            echo "Group (string): " . Util::objectToStr($vatCodeAccount->getGroup()) . "<br />";                                                    // string|null
            echo "GroupCountry (\\PhpTwinfield\\VatGroupCountry): <pre>" . print_r($vatCodeAccount->getGroupCountry(), true) . "</pre><br />";      // VatGroupCountry|null          Country code of the VAT group.
            echo "GroupCountry (string): " . Util::objectToStr($vatCodeAccount->getGroupCountry()) . "<br />";                                      // string|null
            echo "ID: {$vatCodeAccount->getID()}<br />";                                                                                            // int|null                      Line ID.
            echo "LineType: {$vatCodeAccount->getLineType()}<br />";                                                                                // LineType|null                 Is it a vat line or not detail. Use detail in case a part of the calculated vat value should be posted on a different general ledger account.
            echo "Percentage: {$vatCodeAccount->getPercentage()}<br />";                                                                            // float|null                    The VAT percentage.
            echo "Result: {$vatCodeAccount->getResult()}<br />";                                                                                    // int|null                      Result (0 = error, 1 or empty = success).
        }
    }
}

// Copy an existing VatCode to a new entity
if ($executeCopy) {
    try {
        $vatCode = $vatCodeApiConnector->get("VH", $office);
    } catch (ResponseException $e) {
        $vatCode = $e->getReturnedObject();
    }

    $vatCode->setCode("VH2");

    try {
        $vatCodeCopy = $vatCodeApiConnector->send($vatCode);
    } catch (ResponseException $e) {
        $vatCodeCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCodeCopy);
    echo "</pre>";

    echo "Result of copy process: {$vatCodeCopy->getResult()}<br />";
    echo "Code of copied VatCode: {$vatCodeCopy->getCode()}<br />";
    echo "Status of copied VatCode: {$vatCodeCopy->getStatus()}<br />";
}

// Create a new VatCode from scratch, alternatively read an existing VatCode as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $vatCode = new \PhpTwinfield\VatCode;

    // Required values for creating a new VatCode
                                                                                                                                                    //
    $vatCode->setCode('VH2');                                                                                                                       // string|null                  VAT code.
    $vatCode->setName("BTW 21%");                                                                                                                   // string|null                  Name of the VAT.
    $vatCode->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $vatCode->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null
    $vatCode->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                      // Status|null                  For creating and updating active should be used. For deleting deleted should be used.
    //$vatCode->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                   // Status|null
    $vatCode->setType(\PhpTwinfield\Enums\VatType::SALES());                                                                                        // VatType|null

    // Optional values for creating a new VatCode
    $vatCode->setShortName("VH 21%");                                                                                                               // string|null                  Short name of the VAT.

    // The minimum amount of VatCodePercentages linked to a VatCode object is 0
    $vatCodePercentage = new \PhpTwinfield\VatCodePercentage;
    $date = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $vatCodePercentage->setDate($date);                                                                                                             // DateTimeInterface|null       Effective date.
    $vatCodePercentage->setDate(Util::parseDate('20190101'));                                                                                       // string|null
    $vatCodePercentage->setName("BTW 21%");                                                                                                         // string|null                  Name of the VAT line.
    $vatCodePercentage->setPercentage(21);                                                                                                          // float|null                   Percentage of the VAT line.
    $vatCodePercentage->setShortName("VH 21%");                                                                                                     // string|null                  Short name of the VAT line.
    $vatCodePercentage->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                            // Status|null                  For creating and updating active should be used. For deleting deleted should be used.
    //$vatCodePercentage->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                         // Status|null

    // The minimum amount of VatCodeAccounts linked to a VatCodePercentage object is 1
    $vatCodeAccount = new \PhpTwinfield\VatCodeAccount;
    $generalLedger = new \PhpTwinfield\GeneralLedger;
    $generalLedger->setCode('1530');
    $vatCodeAccount->setDim1($generalLedger);                                                                                                       // GeneralLedger|null           General ledger account on which the VAT amount will be posted.
    $vatCodeAccount->setDim1(\PhpTwinfield\GeneralLedger::fromCode('1530'));                                                                        // string|null
    $vatGroup = new \PhpTwinfield\VatGroup;
    $vatGroup->setCode('NL1A');
    $vatCodeAccount->setGroup($vatGroup);                                                                                                           // VatGroup|null                The VAT group.
    $vatCodeAccount->setGroup(\PhpTwinfield\VatGroup::fromCode('NL1A'));                                                                            // string|null
    $vatGroupCountry = new \PhpTwinfield\VatGroupCountry;
    $vatGroupCountry->setCode('NL');
    $vatCodeAccount->setGroupCountry($vatGroupCountry);                                                                                             // VatGroupCountry|null         Country code of the VAT group.
    $vatCodeAccount->setGroupCountry(\PhpTwinfield\VatGroupCountry::fromCode('NL'));                                                                // string|null

    $vatCodeAccount->setID(1);                                                                                                                      // int|null                     Line ID.
    $vatCodeAccount->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Is it a vat line or not detail. Use detail in case a part of the calculated vat value should be posted on a different general ledger account.

    $vatCodeAccount->setPercentage(100);                                                                                                            // float|null                   Percentage of the VAT line.

    $vatCodePercentage->addAccount($vatCodeAccount);                                                                                                // VatCodeAccount               Add a VatCodeAccount object to the VatCodePercentage object
    //$vatCodePercentage->removeAccount(0);                                                                                                         // int                          Remove an account based on the index of the account

    $vatCode->addPercentage($vatCodePercentage);                                                                                                    // VatCodePercentage            Add a VatCodePercentage object to the VatCode object
    //$vatCode->removePercentage(0);                                                                                                                // int                          Remove a percentage based on the index of the percentage within the array

    try {
        $vatCodeNew = $vatCodeApiConnector->send($vatCode);
    } catch (ResponseException $e) {
        $vatCodeNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCodeNew);
    echo "</pre>";

    echo "Result of creation process: {$vatCodeNew->getResult()}<br />";
    echo "Code of new VatCode: {$vatCodeNew->getCode()}<br />";
    echo "Status of new VatCode: {$vatCodeNew->getStatus()}<br />";
}

// Delete a VatCode based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $vatCodeDeleted = $vatCodeApiConnector->delete("VH2", $office);
    } catch (ResponseException $e) {
        $vatCodeDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatCodeDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$vatCodeDeleted->getResult()}<br />";
    echo "Code of deleted VatCode: {$vatCodeDeleted->getCode()}<br />";
    echo "Status of deleted VatCode: {$vatCodeDeleted->getStatus()}<br />";
}
