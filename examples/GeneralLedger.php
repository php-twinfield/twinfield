<?php

/* GeneralLedger
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Dimensions/BalanceSheet and https://accounting.twinfield.com/UI/#/Dimensions/ProfitAndLoss
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* GeneralLedger API Connector
 * \PhpTwinfield\ApiConnectors\GeneralLedgerApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$generalLedgerApiConnector = new \PhpTwinfield\ApiConnectors\GeneralLedgerApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all generalLedgers
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
 *                         Available options:      office, level, section, dimtype, modifiedsince, group
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         level	               Specifies the dimension level.
 *                         Available values:       1, 2, 3, 4, 5, 6
 *                         Usage:                  $options['level'] = 2;
 *
 *                         section	               Restricts to financial or time & expenses dimensions.
 *                         Available values:       financials, teq
 *                         Usage:                  $options['section'] = 'financials';
 *
 *                         dimtype	               Specifies the dimension type.
 *                         Available values:       BAS, PNL, DEB, CRD, KPL, PRJ, AST, ACT
 *                         Usage:                  $options['dimtype'] = 'BAS';
 *
 *                         modifiedsince	       Restricts to dimensions modified after or at the specified date (and time), format yyyyMMddHHmmss or yyyyMMdd
 *                         Usage:                  $options['modifiedsince'] = '20190101170000' or $options['modifiedsince'] = '20190101';
 *
 *                         group	               Specifies the dimension group (wildcards allowed).
 *                         Usage:                  $options['group'] = 'DIMGROUP';
 *
 */

//List all with pattern "1*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> dimtype = 'BAS'
if ($executeListAllWithFilter) {
    $options = array('dimtype' => 'BAS');

    try {
        $generalLedgers = $generalLedgerApiConnector->listAll("1*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $generalLedgers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedgers);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $generalLedgers = $generalLedgerApiConnector->listAll();
    } catch (ResponseException $e) {
        $generalLedgers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedgers);
    echo "</pre>";
}

/* GeneralLedger
 * \PhpTwinfield\GeneralLedger
 * Available getters: getBeginPeriod, getBeginYear, getBehaviour, getCode, getEndPeriod, getEndYear, getGroup, getInUse, getMessages, getName, getOffice, getResult, getShortName, getStatus, getTouched, getType, getWebsite, hasMessages, getFinancials
 * Available setters: setBeginPeriod, setBeginYear, setBehaviour, setCode, setEndPeriod, setEndYear, setGroup, setName, setOffice, setShortName, setStatus, setType, setFinancials
 */

/* GeneralLedgerFinancials
 * \PhpTwinfield\GeneralLedgerFinancials
 * Available getters: getAccountType, getLevel, getMatchType, getMessages, getResult, getSubAnalyse, getVatCode, getVatCodeFixed, getChildValidations, hasMessages
 * Available setters: setAccountType, setLevel, setMatchType, setSubAnalyse, setVatCode, setVatCodeFixed, addChildValidation, removeChildValidation
 */

/* GeneralLedgerChildValidation
 * \PhpTwinfield\GeneralLedgerChildValidation
 * Available getters: getElementValue, getLevel, getMessages, getResult, getType, hasMessages
 * Available setters: setElementValue, setLevel, setType
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($generalLedgers as $key => $generalLedger) {
        echo "GeneralLedger {$key}<br />";
        echo "Code: {$generalLedger->getCode()}<br />";
        echo "Name: {$generalLedger->getName()}<br /><br />";
    }
}

// Read a GeneralLedger based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $generalLedger = $generalLedgerApiConnector->get("1000", "BAS", $office);
    } catch (ResponseException $e) {
        $generalLedger = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedger);
    echo "</pre>";

    echo "GeneralLedger<br />";
    echo "BeginPeriod: {$generalLedger->getBeginPeriod()}<br />";                                                                               			// int|null                         Determines together with beginyear the period from which the dimension may be used.
    echo "BeginYear: {$generalLedger->getBeginYear()}<br />";                                                                               			    // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    echo "Behaviour: {$generalLedger->getBehaviour()}<br />";                                                                                   		    // Behaviour|null                   Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$generalLedger->getCode()}<br />";                                                                                   					    // string|null                      Dimension code, must be compliant with the mask of the BAS or PNL Dimension type.
    echo "EndPeriod: {$generalLedger->getEndPeriod()}<br />";                                                                               			    // int|null                         Determines together with endyear the period till which the dimension may be used.
    echo "EndYear: {$generalLedger->getEndYear()}<br />";                                                                               			        // int|null                         Determines together with endperiod the period till which the dimension may be used.
    echo "Group (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($generalLedger->getGroup(), true) . "</pre><br />";                      			    // DimensionGroup|null              Sets the dimension group. See Dimension group.
    echo "Group (string): " . Util::objectToStr($generalLedger->getGroup()) . "<br />";                                                              		// string|null
    echo "InUse (bool): {$generalLedger->getInUse()}<br />";                                                                                   			    // bool|null                        Indicates if the balance or profit and loss account is used in a financial transaction or linked to a VAT code or linked to an article or not in use at all. Read-only attribute.
    echo "InUse (string): " . Util::formatBoolean($generalLedger->getInUse()) . "<br />";                                                                   // string|null

    if ($generalLedger->hasMessages()) {                                                                                              					    // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($generalLedger->getMessages(), true) . "<br />";                                                  					    // Array|null                       (Error) messages.
    }

    echo "Name: {$generalLedger->getName()}<br />";                                                                                   					    // string|null                      Name of the dimension.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($generalLedger->getOffice(), true) . "</pre><br />";                      					    // Office|null                      Office code.
    echo "Office (string): " . Util::objectToStr($generalLedger->getOffice()) . "<br />";                                                              		// string|null
    echo "Result: {$generalLedger->getResult()}<br />";                                                                               					    // int|null                         Result (0 = error, 1 or empty = success).
    echo "ShortName: {$generalLedger->getShortName()}<br />";                                                                         					    // string|null                      Short name of the dimension.
    echo "Status: {$generalLedger->getStatus()}<br />";                                                                               					    // Status|null                      Status of the generalLedger.
    echo "Touched: {$generalLedger->getTouched()}<br />";                                                                                                   // int|null                         Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($generalLedger->getType(), true) . "</pre><br />";                                         // DimensionType|null               Dimension type. See Dimension type. Dimension type of balance accounts is BAS and type of profit and loss is PNL.
    echo "Type (string): " . Util::objectToStr($generalLedger->getType()) . "<br />";                                                                       // string|null
    echo "UID: {$generalLedger->getUID()}<br />";                                                                                                           // string|null                      Unique identification of the dimension. Read-only attribute.

    echo "GeneralLedgerFinancials<br />";
    $generalLedgerFinancials = $generalLedger->getFinancials();                                                                           			        // GeneralLedgerFinancials|null     GeneralLedgerFinancials object.

    echo "AccountType: {$generalLedgerFinancials->getAccountType()}<br />";                                                                                 // AccountType|null                 Fixed value balance.
    echo "Level: {$generalLedgerFinancials->getLevel()}<br />";                                                                                             // int|null                        	Specifies the dimension level. Normally the level of balance accounts is level 1. Read-only attribute.
    echo "MatchType: {$generalLedgerFinancials->getMatchType()}<br />";                                                                                     // MatchType|null                   Sets the matchable value of the balance account.

    if ($generalLedgerFinancials->hasMessages()) {                                                                                					        // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($generalLedgerFinancials->getMessages(), true) . "<br />";                                    					        // Array|null                       (Error) messages.
    }

    echo "Result: {$generalLedgerFinancials->getResult()}<br />";                                                                                           // int|null                         Result (0 = error, 1 or empty = success).
    echo "SubAnalyse: {$generalLedgerFinancials->getSubAnalyse()}<br />";                                                                                   // SubAnalyse|null                  Is subanalyses needed.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($generalLedgerFinancials->getVatCode(), true) . "</pre><br />";                               // VatCode|null                     Default VAT code.
    echo "VatCode (string): " . Util::objectToStr($generalLedgerFinancials->getVatCode()) . "<br />";                                                       // string|null
    echo "VatCode Fixed (bool): {$generalLedgerFinancials->getVatCodeFixed()}<br />";                                                                       // bool|null
    echo "VatCode Fixed (string): " . Util::formatBoolean($generalLedgerFinancials->getVatCodeFixed()) . "<br />";                                          // string|null

    $generalLedgerChildValidations = $generalLedgerFinancials->getChildValidations();                                                                       // array|null                       Array of GeneralLedgerChildValidations objects. Validation rule when subanalyses is needed.

    foreach ($generalLedgerChildValidations as $key => $generalLedgerChildValidation) {
        echo "GeneralLedgerChildValidation {$key}<br />";

        echo "ElementValue: {$generalLedgerChildValidation->getElementValue()}<br />";                                                                      // string|null
        echo "Level: {$generalLedgerChildValidation->getLevel()}<br />";                                                                                    // int|null

        if ($generalLedgerChildValidation->hasMessages()) {                                                                                				    // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($generalLedgerChildValidation->getMessages(), true) . "<br />";                                    				    // Array|null                       (Error) messages.
        }

        echo "Result: {$generalLedgerChildValidation->getResult()}<br />";                                                                                  // int|null                         Result (0 = error, 1 or empty = success).
        echo "Type: {$generalLedgerChildValidation->getType()}<br />";                                                                                      // ChildValidationType|null
    }
}

// Copy an existing GeneralLedger to a new entity
if ($executeCopy) {
    try {
        $generalLedger = $generalLedgerApiConnector->get("1000", "BAS", $office);
    } catch (ResponseException $e) {
        $generalLedger = $e->getReturnedObject();
    }

    $generalLedger->setCode(null);                                                                                                                          // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$generalLedger->setCode('1100');                                                                                                                      // string|null                      Dimension code, must be compliant with the mask of the BAS or PNL Dimension type.

    try {
        $generalLedgerCopy = $generalLedgerApiConnector->send($generalLedger);
    } catch (ResponseException $e) {
        $generalLedgerCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedgerCopy);
    echo "</pre>";

    echo "Result of copy process: {$generalLedgerCopy->getResult()}<br />";
    echo "Code of copied GeneralLedger: {$generalLedgerCopy->getCode()}<br />";
}

// Create a new GeneralLedger from scratch, alternatively read an existing GeneralLedger as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $generalLedger = new \PhpTwinfield\GeneralLedger;

    // Required values for creating a new GeneralLedger
    $generalLedger->setCode(null);                                                                                                                          // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$generalLedger->setCode('1100');                                                                                                                      // string|null                      Dimension code, must be compliant with the mask of the BAS or PNL Dimension type.
    $generalLedger->setName("Example GeneralLedger");                                                                                                       // string|null                      Name of the dimension.
    $dimensionType = new \PhpTwinfield\DimensionType;
    $dimensionType->setCode('BAS');
    //$dimensionType->setCode('PNL');
    $generalLedger->setType($dimensionType);                                                                                                                // DimensionType|null
    $generalLedger->setType(\PhpTwinfield\DimensionType::fromCode('BAS'));                                                                                  // string|null                 
    //$generalLedger->setType(\PhpTwinfield\DimensionType::fromCode('PNL'));                                                                                  // string|null                 
    $generalLedger->setOffice($office);                                                                                                                     // Office|null                      Office code.
    $generalLedger->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                                 // string|null

    // Optional values for creating a new GeneralLedger
    $generalLedger->setBeginPeriod(0);                                                                                                                      // int|null                         Determines together with beginyear the period from which the dimension may be used.
    $generalLedger->setBeginYear(0);                                                                                                                        // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    $generalLedger->setEndPeriod(0);                                                                                                                        // int|null                         Determines together with endyear the period till which the dimension may be used.
    $generalLedger->setEndYear(0);                                                                                                                          // int|null                         Determines together with endperiod the period till which the dimension may be used.
    $generalLedger->setShortName("ExmplCust");                                                                                                              // string|null                      Short name of the dimension.
    //$generalLedger->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                      // Status|null                      For creating and updating status may be left empty. For deleting deleted should be used.
    //$generalLedger->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                     // Status|null                      In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.

    $dimensionGroup = new \PhpTwinfield\DimensionGroup;
    $dimensionGroup->setCode('DIMGROUP');
    //$generalLedger->setGroup($dimensionGroup);                                                                                                            // DimensionGroup|null              Sets the dimension group. See Dimension group.
    //$generalLedger->setGroup(\PhpTwinfield\DimensionGroup::fromCode("DIMGROUP"));                                                                         // string|null

    $generalLedgerFinancials = new \PhpTwinfield\GeneralLedgerFinancials;
    $generalLedgerFinancials->setAccountType(\PhpTwinfield\Enums\AccountType::BALANCE());                                                                   // AccountType|null
    //$generalLedgerFinancials->setAccountType(\PhpTwinfield\Enums\AccountType::PROFITANDLOSS());                                                           // AccountType|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $generalLedgerFinancials->setVatCode($vatCode);                                                                                                         // VatCode|null                     Default VAT code.
    $generalLedgerFinancials->setVatCode(\PhpTwinfield\VatCode::fromCode('VH'));                                                                            // string|null

    $generalLedger->setFinancials($generalLedgerFinancials);                                                                                                // GeneralLedgerFinancials          Set the GeneralLedgerFinancials object tot the GeneralLedger object

    try {
        $generalLedgerNew = $generalLedgerApiConnector->send($generalLedger);
    } catch (ResponseException $e) {
        $generalLedgerNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedgerNew);
    echo "</pre>";

    echo "Result of creation process: {$generalLedgerNew->getResult()}<br />";
    echo "Code of new GeneralLedger: {$generalLedgerNew->getCode()}<br />";
}

// Delete a GeneralLedger based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $generalLedgerDeleted = $generalLedgerApiConnector->delete("0000", "BAS", $office);
    } catch (ResponseException $e) {
        $generalLedgerDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($generalLedgerDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$generalLedgerDeleted->getResult()}<br />";
}