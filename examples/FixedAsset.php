<?php

/* FixedAsset
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/FixedAssets/ClassicFixedAssets
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
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

/* FixedAsset API Connector
 * \PhpTwinfield\ApiConnectors\FixedAssetApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$fixedAssetApiConnector = new \PhpTwinfield\ApiConnectors\FixedAssetApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all fixedAssets
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
 *                         Available options:      office, level, section, dimtype, modifiedsince, group, matchtype
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         level                   Specifies the dimension level.
 *                         Available values:       1, 2, 3, 4, 5, 6
 *                         Usage:                  $options['level'] = 3;
 *
 *                         section                 Restricts to financial or time & expenses dimensions.
 *                         Available values:       financials, teq
 *                         Usage:                  $options['section'] = 'financials';
 *
 *                         dimtype                 Specifies the dimension type.
 *                         Available values:       BAS, PNL, DEB, CRD, KPL, PRJ, AST, ACT
 *                         Usage:                  $options['dimtype'] = 'AST';
 *
 *                         modifiedsince           Restricts to dimensions modified after or at the specified date (and time), format yyyyMMddHHmmss or yyyyMMdd
 *                         Usage:                  $options['modifiedsince'] = '20190101170000' or $options['modifiedsince'] = '20190101';
 *
 *                         group                   Specifies the dimension group (wildcards allowed).
 *                         Usage:                  $options['group'] = 'DIMGROUP';
 *
 */

//List all with pattern "6*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> modifiedsince = '20190101170000'
if ($executeListAllWithFilter) {
    $options = array('modifiedsince' => '20190101170000');

    try {
        $fixedAssets = $fixedAssetApiConnector->listAll("6*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $fixedAssets = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAssets);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $fixedAssets = $fixedAssetApiConnector->listAll();
    } catch (ResponseException $e) {
        $fixedAssets = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAssets);
    echo "</pre>";
}

/* FixedAsset
 * \PhpTwinfield\FixedAsset
 * Available getters: getBehaviour, getCode, getGroup, getInUse, getMessages, getName, getOffice, getResult, getShortName, getStatus, getTouched, getType, getUID, hasMessages, getFinancials, getFixedAssets
 * Available setters: fromCode, setBehaviour, setCode, setGroup, setName, setOffice, setShortName, setStatus, setType, setTypeFromString, setFinancials, setFixedAssets
 */

/* FixedAssetFinancials
 * \PhpTwinfield\FixedAssetFinancials
 * Available getters: getAccountType, getLevel, getMatchType, getMessages, getResult, getSubAnalyse, getSubstituteWith, getSubstituteWithID, getSubstitutionLevel, getVatCode, getVatCodeFixed, hasMessages
 * Available setters: setAccountType, setLevel, setMatchType, setSubAnalyse, setSubstituteWith, setSubstituteWithID, setSubstitutionLevel, setVatCode, setVatCodeFixed
 */

/* FixedAssetFixedAssets
 * \PhpTwinfield\FixedAssetFixedAssets
 * Available getters: getBeginPeriod, getBeginPeriodLocked, getFreeText1, getFreeText1Locked, getFreeText2, getFreeText2Locked, getFreeText3, getFreeText3Locked, getFreeText4, getFreeText4Locked, getFreeText5, getFreeText5Locked, getLastDepreciation, getLastDepreciationLocked, getMessages, getMethod, getMethodLocked,
 * getNrOfPeriods, getNrOfPeriodsInherited, getNrOfPeriodsLocked, getPercentage, getPercentageLocked, getPurchaseDate, getPurchaseDateLocked, getResidualValue, getResidualValueLocked, getResult, getSellDate, getSellDateLocked, getStatus, getStatusLocked, getStopValue, getStopValueLocked, getTransactionLinesLocked, hasMessages, getTransactionLines
 *
 * Available setters: setBeginPeriod, setBeginPeriodLocked, setFreeText1, setFreeText1Locked, setFreeText2, setFreeText2Locked, setFreeText3, setFreeText3Locked, setFreeText4, setFreeText4Locked, setFreeText5, setFreeText5Locked, setLastDepreciation, setLastDepreciationLocked, setMethod, setMethodLocked,
 * setNrOfPeriods, setNrOfPeriodsInherited, setNrOfPeriodsLocked, setPercentage, setPercentageLocked, setPurchaseDate, setPurchaseDateLocked, setResidualValue, setResidualValueLocked, setSellDate, setSellDateLocked, setStatus, setStatusLocked, setStopValue, setStopValueLocked, setTransactionLinesLocked, addTransactionLine, removeTransactionLine
 *
 */

/* FixedAssetTransactionLine
 * \PhpTwinfield\FixedAssetTransactionLine
 * Available getters: getAmount, getAmountLocked, getCode, getCodeLocked, getDim1, getDim1Locked, getDim2, getDim2Locked, getDim3, getDim3Locked, getDim4, getDim4Locked, getDim5, getDim5Locked, getDim6, getDim6Locked, getLine, getLineLocked, getMessages, getNumber, getNumberLocked, getPeriod, getPeriodLocked, getResult, hasMessages
 * Available setAmount, setAmountLocked, setCode, setCodeLocked, setDim1, setDim1Locked, setDim2, setDim2Locked, setDim3, setDim3Locked, setDim4, setDim4Locked, setDim5, setDim5Locked, setDim6, setDim6Locked, setLine, setLineLocked, setNumber, setNumberLocked, setPeriod, setPeriodLocked
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($fixedAssets as $key => $fixedAsset) {
        echo "FixedAsset {$key}<br />";
        echo "Code: {$fixedAsset->getCode()}<br />";
        echo "Name: {$fixedAsset->getName()}<br /><br />";
    }
}

// Read a FixedAsset based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $fixedAsset = $fixedAssetApiConnector->get("60001", $office);
    } catch (ResponseException $e) {
        $fixedAsset = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAsset);
    echo "</pre>";

    echo "FixedAsset<br />";
    echo "Behaviour: {$fixedAsset->getBehaviour()}<br />";                                                                                              // Behaviour|null                   Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$fixedAsset->getCode()}<br />";                                                                                                        // string|null                      Dimension code, must be compliant with the mask of the AST Dimension type.
    echo "Group (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($fixedAsset->getGroup(), true) . "</pre><br />";                                     // DimensionGroup|null              Sets the dimension group. See Dimension group.
    echo "Group (string): " . Util::objectToStr($fixedAsset->getGroup()) . "<br />";                                                                    // string|null
    echo "InUse (bool): {$fixedAsset->getInUse()}<br />";                                                                                               // bool|null                        Indicates whether the fixed asset is used in a financial transaction or not. Read-only attribute.
    echo "InUse (string): " . Util::formatBoolean($fixedAsset->getInUse()) . "<br />";                                                                  // string|null

    if ($fixedAsset->hasMessages()) {                                                                                                                   // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($fixedAsset->getMessages(), true) . "<br />";                                                                       // Array|null                       (Error) messages.
    }

    echo "Name: {$fixedAsset->getName()}<br />";                                                                                                        // string|null                      Name of the dimension.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($fixedAsset->getOffice(), true) . "</pre><br />";                                           // Office|null                      Office code.
    echo "Office (string): " . Util::objectToStr($fixedAsset->getOffice()) . "<br />";                                                                  // string|null
    echo "Result: {$fixedAsset->getResult()}<br />";                                                                                                    // int|null                         Result (0 = error, 1 or empty = success).
    echo "ShortName: {$fixedAsset->getShortName()}<br />";                                                                                              // string|null                      Not in use.
    echo "Status: {$fixedAsset->getStatus()}<br />";                                                                                                    // Status|null                      Status of the fixed asset.
    echo "Touched: {$fixedAsset->getTouched()}<br />";                                                                                                  // int|null                         Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($fixedAsset->getType(), true) . "</pre><br />";                                        // DimensionType|null               Dimension type. See Dimension type. Dimension type of fixed assets is AST.
    echo "Type (string): " . Util::objectToStr($fixedAsset->getType()) . "<br />";                                                                      // string|null
    echo "UID: {$fixedAsset->getUID()}<br />";                                                                                                          // string|null                      Unique identification of the dimension. Read-only attribute.

    echo "FixedAssetFinancials<br />";
    $fixedAssetFinancials = $fixedAsset->getFinancials();                                                                                               // FixedAssetFinancials|null        FixedAssetFinancials object.

    echo "AccountType: {$fixedAssetFinancials->getAccountType()}<br />";                                                                                // AccountType|null                 Fixed value inherit. Read-only attribute.
    echo "Level: {$fixedAssetFinancials->getLevel()}<br />";                                                                                            // int|null                         Specifies the dimension level. Normally the level of fixed assets is level 3. Read-only attribute.
    echo "MatchType: {$fixedAssetFinancials->getMatchType()}<br />";                                                                                    // MatchType|null                   The matchable value of the balance account. Read-only attribute.

    if ($fixedAssetFinancials->hasMessages()) {                                                                                                         // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($fixedAssetFinancials->getMessages(), true) . "<br />";                                                             // Array|null                       (Error) messages.
    }

    echo "Result: {$fixedAssetFinancials->getResult()}<br />";                                                                                          // int|null                         Result (0 = error, 1 or empty = success).
    echo "SubAnalyse: {$fixedAssetFinancials->getSubAnalyse()}<br />";                                                                                  // SubAnalyse|null                  Is subanalyses needed. Read-only attribute.
    echo "SubstituteWith (\\PhpTwinfield\\CostCenter): <pre>" . print_r($fixedAssetFinancials->getSubstituteWith(), true) . "</pre><br />";             // CostCenter|null                  Dimension code of the cost center.
    echo "SubstituteWith (string): " . Util::objectToStr($fixedAssetFinancials->getSubstituteWith()) . "<br />";                                        // string|null
    echo "SubstituteWithID: {$fixedAssetFinancials->getSubstituteWithID()}<br />";                                                                      // string|null
    echo "SubstitutionLevel: {$fixedAssetFinancials->getSubstitutionLevel()}<br />";                                                                    // int|null                         Dimension level of the cost center.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($fixedAssetFinancials->getVatCode(), true) . "</pre><br />";                              // VatCode|null                     Default VAT code.
    echo "VatCode (string): " . Util::objectToStr($fixedAssetFinancials->getVatCode()) . "<br />";                                                      // string|null
    echo "VatCode Fixed (bool): {$fixedAssetFinancials->getVatCodeFixed()}<br />";                                                                      // bool|null
    echo "VatCode Fixed (string): " . Util::formatBoolean($fixedAssetFinancials->getVatCodeFixed()) . "<br />";                                         // string|null

    $fixedAssetFixedAssets = $fixedAsset->getFixedAssets();                                                                                             // array|null                       Array of FixedAssetFixedAssets objects.

    echo "FixedAssetFixedAssets {$key}<br />";

    echo "BeginPeriod: {$fixedAssetFixedAssets->getBeginPeriod()}<br />";                                                                               // string|null                      Period in YYYY/PP format. The period from which the depreciation to begin.
    echo "BeginPeriod Locked (bool): {$fixedAssetFixedAssets->getBeginPeriodLocked()}<br />";                                                           // bool|null
    echo "BeginPeriod Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getBeginPeriodLocked()) . "<br />";                              // string|null
    echo "FreeText1: {$fixedAssetFixedAssets->getFreeText1()}<br />";                                                                                   // string|null                      Free text field 1 as set on the asset method.
    echo "FreeText1 Locked (bool): {$fixedAssetFixedAssets->getFreeText1Locked()}<br />";                                                               // bool|null
    echo "FreeText1 Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getFreeText1Locked()) . "<br />";                                  // string|null
    echo "FreeText2: {$fixedAssetFixedAssets->getFreeText2()}<br />";                                                                                   // string|null                      Free text field 2 as set on the asset method.
    echo "FreeText2 Locked (bool): {$fixedAssetFixedAssets->getFreeText2Locked()}<br />";                                                               // bool|null
    echo "FreeText2 Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getFreeText2Locked()) . "<br />";                                  // string|null
    echo "FreeText3: {$fixedAssetFixedAssets->getFreeText3()}<br />";                                                                                   // string|null                      Free text field 3 as set on the asset method.
    echo "FreeText3 Locked (bool): {$fixedAssetFixedAssets->getFreeText3Locked()}<br />";                                                               // bool|null
    echo "FreeText3 Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getFreeText3Locked()) . "<br />";                                  // string|null
    echo "FreeText4: {$fixedAssetFixedAssets->getFreeText4()}<br />";                                                                                   // string|null                      Free text field 4 as set on the asset method.
    echo "FreeText4 Locked (bool): {$fixedAssetFixedAssets->getFreeText4Locked()}<br />";                                                               // bool|null
    echo "FreeText4 Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getFreeText4Locked()) . "<br />";                                  // string|null
    echo "FreeText5: {$fixedAssetFixedAssets->getFreeText5()}<br />";                                                                                   // string|null                      Free text field 5 as set on the asset method.
    echo "FreeText5 Locked (bool): {$fixedAssetFixedAssets->getFreeText5Locked()}<br />";                                                               // bool|null
    echo "FreeText5 Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getFreeText5Locked()) . "<br />";                                  // string|null
    echo "LastDepreciation: {$fixedAssetFixedAssets->getLastDepreciation()}<br />";                                                                     // string|null                      Period in YYYY/PP format. The period of the last depreciation of the asset.
    echo "LastDepreciation Locked (bool): {$fixedAssetFixedAssets->getLastDepreciationLocked()}<br />";                                                 // bool|null
    echo "LastDepreciation Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getLastDepreciationLocked()) . "<br />";                    // string|null

    if ($fixedAssetFixedAssets->hasMessages()) {                                                                                                        // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($fixedAssetFixedAssets->getMessages(), true) . "<br />";                                                            // Array|null                       (Error) messages.
    }

    echo "Method (\\PhpTwinfield\\AssetMethod): <pre>" . print_r($fixedAssetFixedAssets->getMethod(), true) . "</pre><br />";                           // AssetMethod|null                 The asset method. See Asset methods.
    echo "Method (string): " . Util::objectToStr($fixedAssetFixedAssets->getMethod()) . "<br />";                                                       // string|null
    echo "Method Locked (bool): {$fixedAssetFixedAssets->getMethodLocked()}<br />";                                                                     // bool|null
    echo "Method Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getMethodLocked()) . "<br />";                                        // string|null
    echo "NrOfPeriods: {$fixedAssetFixedAssets->getNrOfPeriods()}<br />";                                                                               // int|null                         The number of periods over which the asset should be depreciated.
    echo "NrOfPeriods Inherited (bool): {$fixedAssetFixedAssets->getNrOfPeriodsInherited()}<br />";                                                     // bool|null
    echo "NrOfPeriods Inherited (string): " . Util::formatBoolean($fixedAssetFixedAssets->getNrOfPeriodsInherited()) . "<br />";                        // string|null
    echo "NrOfPeriods Locked (bool): {$fixedAssetFixedAssets->getNrOfPeriodsLocked()}<br />";                                                           // bool|null
    echo "NrOfPeriods Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getNrOfPeriodsLocked()) . "<br />";                              // string|null
    echo "Percentage: {$fixedAssetFixedAssets->getPercentage()}<br />";                                                                                 // int|null                         The depreciation percentage.
    echo "Percentage Locked (bool): {$fixedAssetFixedAssets->getPercentageLocked()}<br />";                                                             // bool|null
    echo "Percentage Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getPercentageLocked()) . "<br />";                                // string|null
    echo "PurchaseDate (\\DateTimeInterface): <pre>" . print_r($fixedAssetFixedAssets->getPurchaseDate(), true) . "</pre><br />";                       // DateTimeInterface|null           The purchase date of the asset.
    echo "PurchaseDate (string): " . Util::formatDate($fixedAssetFixedAssets->getPurchaseDate()) . "<br />";                                            // string|null
    echo "PurchaseDate Locked (bool): {$fixedAssetFixedAssets->getPurchaseDateLocked()}<br />";                                                         // bool|null
    echo "PurchaseDate Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getPurchaseDateLocked()) . "<br />";                            // string|null
    echo "ResidualValue (\\Money\\Money): <pre>" . print_r($fixedAssetFixedAssets->getResidualValue(), true) . "</pre><br />";                          // Money|null                       The residual value of the asset at the end of the depreciation duration.
    echo "ResidualValue (string): " . Util::formatMoney($fixedAssetFixedAssets->getResidualValue()) . "<br />";                                         // string|null
    echo "ResidualValue Locked (bool): {$fixedAssetFixedAssets->getResidualValueLocked()}<br />";                                                       // bool|null
    echo "ResidualValue Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getResidualValueLocked()) . "<br />";                          // string|null
    echo "Result: {$fixedAssetFixedAssets->getResult()}<br />";                                                                                         // int|null                         Result (0 = error, 1 or empty = success).
    echo "SellDate (\\DateTimeInterface): <pre>" . print_r($fixedAssetFixedAssets->getSellDate(), true) . "</pre><br />";                               // DateTimeInterface|null           The date the asset is sold.
    echo "SellDate (string): " . Util::formatDate($fixedAssetFixedAssets->getSellDate()) . "<br />";                                                    // string|null
    echo "SellDate Locked (bool): {$fixedAssetFixedAssets->getSellDateLocked()}<br />";                                                                 // bool|null
    echo "SellDate Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getSellDateLocked()) . "<br />";                                    // string|null
    echo "Status: {$fixedAssetFixedAssets->getStatus()}<br />";                                                                                         // FixedAssetsStatus|null           The status of the asset.
    echo "Status Locked (bool): {$fixedAssetFixedAssets->getStatusLocked()}<br />";                                                                     // bool|null                        The value future depreciation should stop at.
    echo "Status Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getStatusLocked()) . "<br />";                                        // string|null
    echo "StopValue (\\Money\\Money): <pre>" . print_r($fixedAssetFixedAssets->getStopValue(), true) . "</pre><br />";                                  // Money|null                       Transaction line amount.
    echo "StopValue (string): " . Util::formatMoney($fixedAssetFixedAssets->getStopValue()) . "<br />";                                                 // string|null
    echo "StopValue Locked (bool): {$fixedAssetFixedAssets->getStopValueLocked()}<br />";                                                               // bool|null
    echo "StopValue Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getStopValueLocked()) . "<br />";                                  // string|null
    echo "TransactionLines Locked (bool): {$fixedAssetFixedAssets->getTransactionLinesLocked()}<br />";                                                 // bool|null
    echo "TransactionLines Locked (string): " . Util::formatBoolean($fixedAssetFixedAssets->getTransactionLinesLocked()) . "<br />";                    // string|null

    $fixedAssetTransactionLines = $fixedAssetFixedAssets->getTransactionLines();                                                                        // array|null                       Array of FixedAssetTransactionLine objects.

    foreach ($fixedAssetTransactionLines as $key => $fixedAssetTransactionLine) {
        echo "FixedAssetTransactionLine {$key}<br />";

        echo "Amount (\\Money\\Money): <pre>" . print_r($fixedAssetTransactionLine->getAmount(), true) . "</pre><br />";                                // Money|null                       Transaction line amount.
        echo "Amount (string): " . Util::formatMoney($fixedAssetTransactionLine->getAmount()) . "<br />";                                               // string|null
        echo "Amount Locked (bool): {$fixedAssetTransactionLine->getAmountLocked()}<br />";                                                             // bool|null
        echo "Amount Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getAmountLocked()) . "<br />";                                // string|null
        echo "Code (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getCode(), true) . "</pre><br />";                  // HasCodeInterface|null            Transaction type code.
        echo "Code (string): " . Util::objectToStr($fixedAssetTransactionLine->getCode()) . "<br />";                                                   // string|null
        echo "Code Locked (bool): {$fixedAssetTransactionLine->getCodeLocked()}<br />";                                                                 // bool|null
        echo "Code Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getCodeLocked()) . "<br />";                                    // string|null
        echo "Dim1 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim1(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 1 of the tranaction line.
        echo "Dim1 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim1()) . "<br />";                                                   // string|null
        echo "Dim1 Locked (bool): {$fixedAssetTransactionLine->getDim1Locked()}<br />";                                                                 // bool|null
        echo "Dim1 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim1Locked()) . "<br />";                                    // string|null
        echo "Dim2 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim2(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 2 of the tranaction line.
        echo "Dim2 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim2()) . "<br />";                                                   // string|null
        echo "Dim2 Locked (bool): {$fixedAssetTransactionLine->getDim2Locked()}<br />";                                                                 // bool|null
        echo "Dim2 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim2Locked()) . "<br />";                                    // string|null
        echo "Dim3 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim3(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 3 of the tranaction line.
        echo "Dim3 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim3()) . "<br />";                                                   // string|null
        echo "Dim3 Locked (bool): {$fixedAssetTransactionLine->getDim3Locked()}<br />";                                                                 // bool|null
        echo "Dim3 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim3Locked()) . "<br />";                                    // string|null
        echo "Dim4 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim4(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 4 of the tranaction line.
        echo "Dim4 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim4()) . "<br />";                                                   // string|null
        echo "Dim4 Locked (bool): {$fixedAssetTransactionLine->getDim4Locked()}<br />";                                                                 // bool|null
        echo "Dim4 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim4Locked()) . "<br />";                                    // string|null
        echo "Dim5 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim5(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 5 of the tranaction line.
        echo "Dim5 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim5()) . "<br />";                                                   // string|null
        echo "Dim5 Locked (bool): {$fixedAssetTransactionLine->getDim5Locked()}<br />";                                                                 // bool|null
        echo "Dim5 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim5Locked()) . "<br />";                                    // string|null
        echo "Dim6 (\\PhpTwinfield\\HasCodeInterface): <pre>" . print_r($fixedAssetTransactionLine->getDim6(), true) . "</pre><br />";                  // HasCodeInterface|null            Dimension 6 of the tranaction line.
        echo "Dim6 (string): " . Util::objectToStr($fixedAssetTransactionLine->getDim6()) . "<br />";                                                   // string|null
        echo "Dim6 Locked (bool): {$fixedAssetTransactionLine->getDim6Locked()}<br />";                                                                 // bool|null
        echo "Dim6 Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getDim6Locked()) . "<br />";                                    // string|null
        echo "Line: {$fixedAssetTransactionLine->getLine()}<br />";                                                                                     // int|null                         Transaction line number.
        echo "Line Locked (bool): {$fixedAssetTransactionLine->getLineLocked()}<br />";                                                                 // bool|null
        echo "Line Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getLineLocked()) . "<br />";                                    // string|null

        if ($fixedAssetTransactionLine->hasMessages()) {                                                                                                // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($fixedAssetTransactionLine->getMessages(), true) . "<br />";                                                    // Array|null                       (Error) messages.
        }

        echo "Number: {$fixedAssetTransactionLine->getNumber()}<br />";                                                                                 // int|null                         Transaction number.
        echo "Number Locked (bool): {$fixedAssetTransactionLine->getNumberLocked()}<br />";                                                             // bool|null
        echo "Number Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getNumberLocked()) . "<br />";                                // string|null
        echo "Period: {$fixedAssetTransactionLine->getPeriod()}<br />";                                                                                 // string|null                      Period in YYYY/PP format. Period of the transaction.
        echo "Period Locked (bool): {$fixedAssetTransactionLine->getPeriodLocked()}<br />";                                                             // bool|null
        echo "Period Locked (string): " . Util::formatBoolean($fixedAssetTransactionLine->getPeriodLocked()) . "<br />";                                // string|null
        echo "Result: {$fixedAssetTransactionLine->getResult()}<br />";                                                                                 // int|null                         Result (0 = error, 1 or empty = success).
    }
}

// Copy an existing FixedAsset to a new entity
if ($executeCopy) {
    try {
        $fixedAsset = $fixedAssetApiConnector->get("60001", $office);
    } catch (ResponseException $e) {
        $fixedAsset = $e->getReturnedObject();
    }

    $fixedAsset->setCode(null);                                                                                                                         // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$fixedAsset->setCode('60002');                                                                                                                    // string|null                      Dimension code, must be compliant with the mask of the AST Dimension type.

    try {
        $fixedAssetCopy = $fixedAssetApiConnector->send($fixedAsset);
    } catch (ResponseException $e) {
        $fixedAssetCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAssetCopy);
    echo "</pre>";

    echo "Result of copy process: {$fixedAssetCopy->getResult()}<br />";
    echo "Code of copied FixedAsset: {$fixedAssetCopy->getCode()}<br />";
    echo "Status of copied FixedAsset: {$fixedAssetCopy->getStatus()}<br />";
}

// Create a new FixedAsset from scratch, alternatively read an existing FixedAsset as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $fixedAsset = new \PhpTwinfield\FixedAsset;

    // Required values for creating a new FixedAsset
    $fixedAsset->setCode(null);                                                                                                                         // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$fixedAsset->setCode('60002');                                                                                                                    // string|null                      Dimension code, must be compliant with the mask of the AST Dimension type.
    $fixedAsset->setName("Example FixedAsset");                                                                                                         // string|null                      Name of the dimension.
    $fixedAsset->setOffice($office);                                                                                                                    // Office|null                      Office code.
    $fixedAsset->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                                // string|null

    // Optional values for creating a new FixedAsset
    //$fixedAsset->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                     // Status|null                      For creating and updating status may be left empty. For deleting deleted should be used.
    //$fixedAsset->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                    // Status|null                      In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.

    $dimensionGroup = new \PhpTwinfield\DimensionGroup;
    $dimensionGroup->setCode('DIMGROUP');
    //$fixedAsset->setGroup($dimensionGroup);                                                                                                           // DimensionGroup|null              Sets the dimension group. See Dimension group.
    //$fixedAsset->setGroup(\PhpTwinfield\DimensionGroup::fromCode("DIMGROUP"));                                                                        // string|null

    $fixedAssetFinancials = new \PhpTwinfield\FixedAssetFinancials;
    $substituteWith = new \PhpTwinfield\CostCenter;
    $substituteWith->getCode('00001');
    $fixedAssetFinancials->setSubstituteWith($substituteWith);                                                                                          // CostCenter|null                  Dimension code of the cost center.
    $fixedAssetFinancials->setSubstituteWith(\PhpTwinfield\CostCenter::fromCode('00001'));                                                              // string|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $fixedAssetFinancials->setVatCode($vatCode);                                                                                                        // VatCode|null                     Default VAT code.
    $fixedAssetFinancials->setVatCode(\PhpTwinfield\VatCode::fromCode('VH'));                                                                           // string|null

    $fixedAsset->setFinancials($fixedAssetFinancials);                                                                                                  // FixedAssetFinancials             Set the FixedAssetFinancials object tot the FixedAsset object

    $fixedAssetFixedAssets = new \PhpTwinfield\FixedAssetFixedAssets;
    $fixedAssetFixedAssets->setBeginPeriod('2019/01');                                                                                                  // string|null                      Period in YYYY/PP format. The period from which the depreciation to begin.
    $fixedAssetFixedAssets->setFreeText1('1');                                                                                                          // string|null                      Free text field 1 as set on the asset method.
    $fixedAssetFixedAssets->setFreeText2('3');                                                                                                          // string|null                      Free text field 2 as set on the asset method.
    $fixedAssetFixedAssets->setFreeText3('3');                                                                                                          // string|null                      Free text field 3 as set on the asset method.
    $fixedAssetFixedAssets->setFreeText4('4');                                                                                                          // string|null                      Free text field 4 as set on the asset method.
    $fixedAssetFixedAssets->setFreeText5('5');                                                                                                          // string|null                      Free text field 5 as set on the asset method.
    $fixedAssetFixedAssets->setLastDepreciation('2019/12');                                                                                             // string|null                      Period in YYYY/PP format. The period of the last depreciation of the asset.
    $method = new \PhpTwinfield\AssetMethod;
    $method->setCode('101');
    $fixedAssetFixedAssets->setMethod($currency);                                                                                                       // AssetMethod|null                 The asset method. See Asset methods.
    $fixedAssetFixedAssets->setMethod(\PhpTwinfield\AssetMethod::fromCode('101'));                                                                      // string|null
    $fixedAssetFixedAssets->setNrOfPeriods(12);                                                                                                         // int|null                         The number of periods over which the asset should be depreciated.
    //$fixedAssetFixedAssets->setPercentage(8);                                                                                                         // int|null                         The depreciation percentage.
    $purchaseDate = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    $fixedAssetFixedAssets->setPurchaseDate($purchaseDate);                                                                                             // DateTimeInterface|null           The purchase date of the asset.
    $fixedAssetFixedAssets->setPurchaseDate(Util::parseDate('20190101'));                                                                               // string|null
    $fixedAssetFixedAssets->setResidualValue(\Money\Money::EUR(50000));                                                                                 // Money|null                       The residual value of the asset at the end of the depreciation duration. (Equals 500.00 EUR)
    $sellDate = \DateTime::createFromFormat('d-m-Y', '01-01-2019');
    //$fixedAssetFixedAssets->setSellDate($sellDate);                                                                                                   // DateTimeInterface|null           The date the asset is sold.
    //$fixedAssetFixedAssets->setSellDate(Util::parseDate('20190101'));                                                                                 // string|null
    $fixedAssetFixedAssets->setStopValue(\Money\Money::EUR(0));                                                                                         // Money|null                       The value future depreciation should stop at. (Equals 0.00 EUR)
    
    $fixedAsset->setFixedAssets($fixedAssetFixedAssets);                                                                                                // FixedAssetFixedAssets            Set the FixedAssetFixedAssets object tot the FixedAsset object

    try {
        $fixedAssetNew = $fixedAssetApiConnector->send($fixedAsset);
    } catch (ResponseException $e) {
        $fixedAssetNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAssetNew);
    echo "</pre>";

    echo "Result of creation process: {$fixedAssetNew->getResult()}<br />";
    echo "Code of new FixedAsset: {$fixedAssetNew->getCode()}<br />";
    echo "Status of new FixedAsset: {$fixedAssetNew->getStatus()}<br />";
}

// Delete a FixedAsset based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $fixedAssetDeleted = $fixedAssetApiConnector->delete("60002", $office);
    } catch (ResponseException $e) {
        $fixedAssetDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($fixedAssetDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$fixedAssetDeleted->getResult()}<br />";
    echo "Code of deleted FixedAsset: {$fixedAssetDeleted->getCode()}<br />";
    echo "Status of deleted FixedAsset: {$fixedAssetDeleted->getStatus()}<br />";
}