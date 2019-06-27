<?php

/* AssetMethod
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/FixedAssets/ClassicAssetClasses
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* AssetMethod API Connector
 * \PhpTwinfield\ApiConnectors\AssetMethodApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$assetMethodApiConnector = new \PhpTwinfield\ApiConnectors\AssetMethodApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all assetMethods
 * @param string $pattern  The search pattern. May contain wildcards * and ?
 * @param int    $field    The search field determines which field or fields will be searched. The available fields
 *                         depends on the finder type. Passing a value outside the specified values will cause an
 *                         error. 0 searches on the code or name field, 1 searches only on the code field,
 *                         2 searches only on the name field
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
 */

//List all with pattern "10*", field 0 (= search code or name), firstRow 1, maxRows 10
if ($executeListAllWithFilter) {
    try {
        $assetMethods = $assetMethodApiConnector->listAll("10*", 0, 1, 10);
    } catch (ResponseException $e) {
        $assetMethods = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethods);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $assetMethods = $assetMethodApiConnector->listAll();
    } catch (ResponseException $e) {
        $assetMethods = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethods);
    echo "</pre>";
}

/* AssetMethod
 * \PhpTwinfield\AssetMethod
 * Available getters: getCalcMethod, getCode, getCreated, getDepreciateReconciliation, getInUse, getMessages, getModified, getName, getNrOfPeriods, getOffice, getPercentage, getResult, getShortName, getStatus, getTouched, getUser, hasMessages, getBalanceAccounts, getFreeTexts, getProfitLossAccounts
 * Available setters: fromCode, setBalanceAccounts, setCalcMethod, setCode, setDepreciateReconciliation, setName, setNrOfPeriods, setOffice, setPercentage, setProfitLossAccounts, setShortName, setStatus, addFreeText, removeFreeText
 */

/* AssetMethodBalanceAccounts
 * \PhpTwinfield\AssetMethodBalanceAccounts
 * Available getters: getAssetsToActivate, getDepreciation, getDepreciationGroup, getMessages, getPurchaseValue, getPurchaseValueGroup, getReconciliation, getResult, getToBeInvoiced, hasMessages
 * Available setters: setAssetsToActivate, setDepreciation, setDepreciationGroup, setPurchaseValue, setPurchaseValueGroup, setReconciliation, setToBeInvoiced
 */

/* AssetMethodProfitLossAccounts
 * \PhpTwinfield\AssetMethodProfitLossAccounts
 * Available getters: getDepreciation, getMessages, getReconciliation, getResult, getSales, hasMessages
 * Available setters: setDepreciation, setReconciliation, setSales
 */

/* AssetMethodFreeText
 * \PhpTwinfield\AssetMethodFreeText
 * Available getters: getElementValue, getID, getMessages, getResult, getType, hasMessages
 * Available setters: setElementValue, setID, setType
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($assetMethods as $key => $assetMethod) {
        echo "AssetMethod {$key}<br />";
        echo "Code: {$assetMethod->getCode()}<br />";
        echo "Name: {$assetMethod->getName()}<br /><br />";
    }
}

// Read an AssetMethod based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $assetMethod = $assetMethodApiConnector->get("101", $office);
    } catch (ResponseException $e) {
        $assetMethod = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethod);
    echo "</pre>";

    echo "AssetMethod<br />";
    echo "CalcMethod: {$assetMethod->getCalcMethod()}<br />";                                                                                                   // CalcMethod|null                      The calculation method.
    echo "Code: {$assetMethod->getCode()}<br />";                                                                                                               // string|null                          Asset method code.
    echo "Created (\\DateTimeInterface): <pre>" . print_r($assetMethod->getCreated(), true) . "</pre><br />";                                                   // \DateTimeInterface|null              Date/time the asset method is created.
    echo "Created (string): " . Util::formatDate($assetMethod->getCreated()) . "<br />";                                                                        // string|null
    echo "DepreciateReconciliation: {$assetMethod->getDepreciateReconciliation()}<br />";                                                                       // DepreciateReconciliation|null        Depreciate as of the revaluation date. Depreciate in retrospect, back to the purchase date.
    echo "InUse (bool): {$assetMethod->getInUse()}<br />";                                                                                                      // bool|null
    echo "InUse (string): " . Util::formatBoolean($assetMethod->getInUse()) . "<br />";                                                                         // string|null

    if ($assetMethod->hasMessages()) {                                                                                                                          // bool                                 Object contains (error) messages true/false.
        echo "Messages: " . print_r($assetMethod->getMessages(), true) . "<br />";                                                                              // Array|null                           (Error) messages.
    }

    echo "Modified (\\DateTimeInterface): <pre>" . print_r($assetMethod->getModified(), true) . "</pre><br />";                                                 // \DateTimeInterface|null              Date/time the asset method is modified the last time.
    echo "Modified (string): " . Util::formatDate($assetMethod->getModified()) . "<br />";                                                                      // string|null
    echo "Name: {$assetMethod->getName()}<br />";                                                                                                               // string|null                          The name of the asset method.
    echo "NrOfPeriods: {$assetMethod->getNrOfPeriods()}<br />";                                                                                                 // int|null                             The number of periods over which the asset linked to the asset method should be depreciated.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($assetMethod->getOffice(), true) . "</pre><br />";                                                  // Office|null                          Office code.
    echo "Office (string): " . Util::objectToStr($assetMethod->getOffice()) . "<br />";                                                                         // string|null
    echo "Percentage: {$assetMethod->getPercentage()}<br />";                                                                                                   // int|null
    echo "Result: {$assetMethod->getResult()}<br />";                                                                                                           // int|null                             Result (0 = error, 1 or empty = success).
    echo "ShortName: {$assetMethod->getShortName()}<br />";                                                                                                     // string|null                          The short name of the asset method.
    echo "Status: {$assetMethod->getStatus()}<br />";                                                                                                           // Status|null                          Status of the asset method.
    echo "Touched: {$assetMethod->getTouched()}<br />";                                                                                                         // int|null                             Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "User (\\PhpTwinfield\\User): <pre>" . print_r($assetMethod->getUser(), true) . "</pre><br />";                                                        // User|null                            The user who modified the asset method the last time.
    echo "User (string): " . Util::objectToStr($assetMethod->getUser()) . "<br />";                                                                             // string|null

    echo "AssetMethodBalanceAccounts<br />";
    $assetMethodBalanceAccounts = $assetMethod->getBalanceAccounts();                                                                                           // AssetMethodBalanceAccounts|null      AssetMethodBalanceAccounts object.

    echo "AssetsToActivate (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodBalanceAccounts->getAssetsToActivate(), true) . "</pre><br />";        // GeneralLedger|null                   Assets to activate balance sheet.
    echo "AssetsToActivate (string): " . Util::objectToStr($assetMethodBalanceAccounts->getAssetsToActivate()) . "<br />";                                      // string|null
    echo "Depreciation (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodBalanceAccounts->getDepreciation(), true) . "</pre><br />";                // GeneralLedger|null                   Cumulative depreciation balance sheet.
    echo "Depreciation (string): " . Util::objectToStr($assetMethodBalanceAccounts->getDepreciation()) . "<br />";                                              // string|null
    echo "DepreciationGroup (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($assetMethodBalanceAccounts->getDepreciationGroup(), true) . "</pre><br />";     // DimensionGroup|null                  Depreciation group.
    echo "DepreciationGroup (string): " . Util::objectToStr($assetMethodBalanceAccounts->getDepreciationGroup()) . "<br />";                                    // string|null

    if ($assetMethodBalanceAccounts->hasMessages()) {                                                                                                           // bool                                 Object contains (error) messages true/false.
        echo "Messages: " . print_r($assetMethodBalanceAccounts->getMessages(), true) . "<br />";                                                               // Array|null                           (Error) messages.
    }

    echo "PurchaseValue (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodBalanceAccounts->getPurchaseValue(), true) . "</pre><br />";              // GeneralLedger|null                   Purchase value balance sheet.
    echo "PurchaseValue (string): " . Util::objectToStr($assetMethodBalanceAccounts->getPurchaseValue()) . "<br />";                                            // string|null
    echo "PurchaseValueGroup (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($assetMethodBalanceAccounts->getPurchaseValueGroup(), true) . "</pre><br />";   // DimensionGroup|null                  Purchase value group.
    echo "PurchaseValueGroup (string): " . Util::objectToStr($assetMethodBalanceAccounts->getPurchaseValueGroup()) . "<br />";                                  // string|null
    echo "Reconciliation (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodBalanceAccounts->getReconciliation(), true) . "</pre><br />";            // GeneralLedger|null                   Revaluation of purchase value balance sheet.
    echo "Reconciliation (string): " . Util::objectToStr($assetMethodBalanceAccounts->getReconciliation()) . "<br />";                                          // string|null
    echo "Result: {$assetMethodBalanceAccounts->getResult()}<br />";                                                                                            // int|null                             Result (0 = error, 1 or empty = success).
    echo "ToBeInvoiced (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodBalanceAccounts->getToBeInvoiced(), true) . "</pre><br />";                // GeneralLedger|null                   Disposal value to be invoiced balance sheet.
    echo "ToBeInvoiced (string): " . Util::objectToStr($assetMethodBalanceAccounts->getToBeInvoiced()) . "<br />";                                              // string|null

    echo "AssetMethodProfitLossAccounts<br />";
    $assetMethodProfitLossAccounts = $assetMethod->getProfitLossAccounts();                                                                                     // AssetMethodProfitLossAccounts|null  AssetMethodProfitLossAccounts object.

    echo "Depreciation (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodProfitLossAccounts->getDepreciation(), true) . "</pre><br />";             // GeneralLedger|null                   Depreciation costs profit and loss account.
    echo "Depreciation (string): " . Util::objectToStr($assetMethodProfitLossAccounts->getDepreciation()) . "<br />";                                           // string|null

    if ($assetMethodProfitLossAccounts->hasMessages()) {                                                                                                        // bool                                 Object contains (error) messages true/false.
        echo "Messages: " . print_r($assetMethodProfitLossAccounts->getMessages(), true) . "<br />";                                                            // Array|null                           (Error) messages.
    }

    echo "Reconciliation (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodProfitLossAccounts->getReconciliation(), true) . "</pre><br />";         // GeneralLedger|null                   Revaluation reserve profit and loss account.
    echo "Reconciliation (string): " . Util::objectToStr($assetMethodProfitLossAccounts->getReconciliation()) . "<br />";                                       // string|null
    echo "Result: {$assetMethodProfitLossAccounts->getResult()}<br />";                                                                                         // int|null                             Result (0 = error, 1 or empty = success).
    echo "Sales (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($assetMethodProfitLossAccounts->getSales(), true) . "</pre><br />";                           // GeneralLedger|null                   Disposal result profit and loss account.
    echo "Sales (string): " . Util::objectToStr($assetMethodProfitLossAccounts->getSales()) . "<br />";                                                         // string|null

    $assetMethodFreeTexts = $assetMethod->getFreeTexts();                                                                                                       // array|null                           Array of AssetMethodFreeText objects.

    foreach ($assetMethodFreeTexts as $key => $assetMethodFreeText) {
        echo "AssetMethodFreeText {$key}<br />";

        echo "ElementValue: {$assetMethodFreeText->getElementValue()}<br />";                                                                                   // string|null                          Free text.
        echo "ID: {$assetMethodFreeText->getID()}<br />";                                                                                                       // int|null                             Free text id. In total, five free text fields are available.

        if ($assetMethodFreeText->hasMessages()) {                                                                                                              // bool                                 Object contains (error) messages true/false.
            echo "Messages: " . print_r($assetMethodFreeText->getMessages(), true) . "<br />";                                                                  // Array|null                           (Error) messages.
        }

        echo "Result: {$assetMethodFreeText->getResult()}<br />";                                                                                               // int|null                             Result (0 = error, 1 or empty = success).
        echo "Type: {$assetMethodFreeText->getType()}<br />";                                                                                                   // FreeTextType|null                    Free text type.
    }
}

// Copy an existing AssetMethod to a new entity
if ($executeCopy) {
    try {
        $assetMethod = $assetMethodApiConnector->get("101", $office);
    } catch (ResponseException $e) {
        $assetMethod = $e->getReturnedObject();
    }

    $assetMethod->setCode('102');                                                                                                                               // string|null                          Dimension code, must be compliant with the mask of the BAS or PNL Dimension type.

    try {
        $assetMethodCopy = $assetMethodApiConnector->send($assetMethod);
    } catch (ResponseException $e) {
        $assetMethodCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethodCopy);
    echo "</pre>";

    echo "Result of copy process: {$assetMethodCopy->getResult()}<br />";
    echo "Code of copied AssetMethod: {$assetMethodCopy->getCode()}<br />";
    echo "Status of copied AssetMethod: {$assetMethodCopy->getStatus()}<br />";
}

// Create a new AssetMethod from scratch, alternatively read an existing AssetMethod as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $assetMethod = new \PhpTwinfield\AssetMethod;

    // Required values for creating a new AssetMethod
    $assetMethod->setCalcMethod(\PhpTwinfield\Enums\CalcMethod::NONE());                                                                                        // CalcMethod|null                      The calculation method.
    $assetMethod->setCode('102');                                                                                                                               // string|null
    $assetMethod->setName("Example AssetMethod");                                                                                                               // string|null                          The name of the asset method.
    $assetMethod->setOffice($office);                                                                                                                           // Office|null                          Office code.
    $assetMethod->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                                       // string|null

    $assetMethodBalanceAccounts = new \PhpTwinfield\AssetMethodBalanceAccounts;

    $depreciation = new \PhpTwinfield\GeneralLedger;
    $depreciation->setCode('0150');
    $assetMethodBalanceAccounts->setDepreciation($depreciation);                                                                                                // GeneralLedger|null                   Cumulative depreciation balance sheet.
    $assetMethodBalanceAccounts->setDepreciation(\PhpTwinfield\GeneralLedger::fromCode('0150'));                                                                // string|null
    $purchasevalue = new \PhpTwinfield\GeneralLedger;
    $purchasevalue->setCode('0155');
    $assetMethodBalanceAccounts->setPurchaseValue($purchasevalue);                                                                                              // GeneralLedger|null                   Purchase value balance sheet.
    $assetMethodBalanceAccounts->setPurchaseValue(\PhpTwinfield\GeneralLedger::fromCode('0155'));                                                               // string|null

    $assetMethod->setBalanceAccounts($assetMethodBalanceAccounts);                                                                                              // AssetMethodBalanceAccounts           Set the AssetMethodBalanceAccounts object tot the AssetMethod object

    $assetMethodProfitLossAccounts = new \PhpTwinfield\AssetMethodProfitLossAccounts;

    $depreciation = new \PhpTwinfield\GeneralLedger;
    $depreciation->setCode('4750');
    $assetMethodProfitLossAccounts->setDepreciation($depreciation);                                                                                             // GeneralLedger|null                   Depreciation costs profit and loss account.
    $assetMethodProfitLossAccounts->setDepreciation(\PhpTwinfield\GeneralLedger::fromCode('4750'));                                                             // string|null

    $assetMethod->setProfitLossAccounts($assetMethodProfitLossAccounts);                                                                                        // AssetMethodProfitLossAccounts        Set the AssetMethodProfitLossAccounts object tot the AssetMethod object

    // Optional values for creating a new AssetMethod
    //$assetMethod->setDepreciateReconciliation(\PhpTwinfield\Enums\DepreciateReconciliation::FROMPURCHASEDATE());                                              // DepreciateReconciliation|null        Depreciate as of the revaluation date. Depreciate in retrospect, back to the purchase date.
    //$assetMethod->setNrOfPeriods(1);                                                                                                                          // int|null                             The number of periods over which the asset linked to the asset method should be depreciated.
    //$assetMethod->setPercentage(1);                                                                                                                           // int|null
    $assetMethod->setShortName("ExmplAsstMthd");                                                                                                                // string|null                          The short name of the asset method.
    $assetMethod->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                              // Status|null                          For creating and updating status may be left empty. For deleting deleted should be used.
    //$assetMethod->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                           // Status|null                          In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.

    $assetsToActivate = new \PhpTwinfield\GeneralLedger;
    $assetsToActivate->setCode('0180');
    //$assetMethodBalanceAccounts->setAssetsToActivate($assetsToActivate);                                                                                      // GeneralLedger|null                   Assets to activate balance sheet.
    //$assetMethodBalanceAccounts->setAssetsToActivate(\PhpTwinfield\GeneralLedger::fromCode('0180'));                                                          // string|null
    $depreciationGroup = new \PhpTwinfield\DimensionGroup;
    $depreciationGroup->setCode('EXAMPLEGRP');
    //$assetMethodBalanceAccounts->setDepreciationGroup($depreciationGroup);                                                                                    // DimensionGroup|null                  Depreciation group.
    //$assetMethodBalanceAccounts->setDepreciationGroup(\PhpTwinfield\DimensionGroup::fromCode('EXAMPLEGRP'));                                                  // string|null
    $purchasevalueGroup = new \PhpTwinfield\DimensionGroup;
    $purchasevalueGroup->setCode('EXAMPLEGRP');
    //$assetMethodBalanceAccounts->setPurchaseValueGroup($purchasevalueGroup);                                                                                  // DimensionGroup|null                  Purchase value group.
    //$assetMethodBalanceAccounts->setPurchaseValueGroup(\PhpTwinfield\DimensionGroup::fromCode('EXAMPLEGRP'));                                                 // string|null
    $reconciliation = new \PhpTwinfield\GeneralLedger;
    $reconciliation->setCode('0845');
    //$assetMethodBalanceAccounts->setReconciliation($reconciliation);                                                                                          // GeneralLedger|null                   Revaluation of purchase value balance sheet.
    //$assetMethodBalanceAccounts->setReconciliation(\PhpTwinfield\GeneralLedger::fromCode('0845'));                                                            // string|null
    $toBeInvoiced = new \PhpTwinfield\GeneralLedger;
    $toBeInvoiced->setCode('1260');
    //$assetMethodBalanceAccounts->setToBeInvoiced($toBeInvoiced);                                                                                              // GeneralLedger|null                   Disposal value to be invoiced balance sheet.
    //$assetMethodBalanceAccounts->setToBeInvoiced(\PhpTwinfield\GeneralLedger::fromCode('1260'));                                                              // string|null

    $assetMethod->setBalanceAccounts($assetMethodBalanceAccounts);                                                                                              // AssetMethodBalanceAccounts           Set the AssetMethodBalanceAccounts object tot the AssetMethod object

    $reconciliation = new \PhpTwinfield\GeneralLedger;
    $reconciliation->setCode('0845');
    //$assetMethodProfitLossAccounts->setReconciliation($reconciliation);                                                                                       // GeneralLedger|null                   Revaluation reserve profit and loss account.
    //$assetMethodProfitLossAccounts->setReconciliation(\PhpTwinfield\GeneralLedger::fromCode('0845'));                                                         // string|null
    $sales = new \PhpTwinfield\GeneralLedger;
    $sales->setCode('0155');
    //$assetMethodProfitLossAccounts->setSales($sales);                                                                                                         // GeneralLedger|null                   Disposal result profit and loss account.
    //$assetMethodProfitLossAccounts->setSales(\PhpTwinfield\GeneralLedger::fromCode('0155'));                                                                  // string|null

    $assetMethod->setProfitLossAccounts($assetMethodProfitLossAccounts);                                                                                        // AssetMethodProfitLossAccounts        Set the AssetMethodProfitLossAccounts object tot the AssetMethod object

    // The minimum amount of AssetMethodFreeTexts linked to an AssetMethod object is 0, the maximum amount is 5
    $assetMethodFreeText = new \PhpTwinfield\AssetMethodFreeText;

    $assetMethodFreeText->setID(1);                                                                                                                             // int|null                             Free text id. In total, five free text fields are available.
    $assetMethodFreeText->setType(\PhpTwinfield\Enums\FreeTextType::TEXT());                                                                                    // FreeTextType|null                    Free text type.
    $assetMethodFreeText->setElementValue('Example free text');                                                                                                 // string|null                          Free text.

    $assetMethod->addFreeText($assetMethodFreeText);                                                                                                            // AssetMethodFreeText                  Add an AssetMethodFreeText object to the AssetMethod object
    //$assetMethod->removeFreeText(0);                                                                                                                          // int                                  Remove a free text based on the index of the free text

    try {
        $assetMethodNew = $assetMethodApiConnector->send($assetMethod);
    } catch (ResponseException $e) {
        $assetMethodNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethodNew);
    echo "</pre>";

    echo "Result of creation process: {$assetMethodNew->getResult()}<br />";
    echo "Code of new AssetMethod: {$assetMethodNew->getCode()}<br />";
    echo "Status of new AssetMethod: {$assetMethodNew->getStatus()}<br />";
}

// Delete an AssetMethod based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $assetMethodDeleted = $assetMethodApiConnector->delete("102", $office);
    } catch (ResponseException $e) {
        $assetMethodDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($assetMethodDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$assetMethodDeleted->getResult()}<br />";
    echo "Code of deleted AssetMethod: {$assetMethodDeleted->getCode()}<br />";
    echo "Status of deleted AssetMethod: {$assetMethodDeleted->getStatus()}<br />";
}