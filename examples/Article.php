<?php

/* Article
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Sales/ClassicItems
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Article API Connector
 * \PhpTwinfield\ApiConnectors\ArticleApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$articleApiConnector = new \PhpTwinfield\ApiConnectors\ArticleApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all articles
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
 *                         Available options:      office, vat
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         vat                     Return only items configured with a value including or excluding the VAT.
 *                         Available values:       inclusive, exclusive
 *                         Usage:                  $options['vat'] = 'inclusive';
 *
 */

//List all with pattern "O*", field 0 (= search code or number), firstRow 1, maxRows 10, options -> vat = 'inclusive'
if ($executeListAllWithFilter) {
    $options = array('vat' => 'inclusive');

    try {
        $articles = $articleApiConnector->listAll("O*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $articles = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($articles);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $articles = $articleApiConnector->listAll();
    } catch (ResponseException $e) {
        $articles = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($articles);
    echo "</pre>";
}

/* Article
 * \PhpTwinfield\Article
 * Available getters: getAllowChangePerformanceType, getAllowChangePerformanceTypeToString, getAllowChangeUnitsPrice, getAllowChangeUnitsPriceToString, getAllowChangeVatCode, getAllowChangeVatCodeToString, getAllowDecimalQuantity, getAllowDecimalQuantityToString, getAllowDiscountOrPremium,
 * getAllowDiscountOrPremiumToString, getCode, getMessages, getName, getOffice, getOfficeToString, getPercentage, getPercentageToString, getPerformanceType, getResult, getShortName, getStatus, getType, getUnitNamePlural, getUnitNameSingular, getVatCode, getVatCodeToString, hasMessages, getLines
 *
 * Available setters: setAllowChangePerformanceType, setAllowChangePerformanceTypeFromString, setAllowChangeUnitsPrice, setAllowChangeUnitsPriceFromString, setAllowChangeVatCode, setAllowChangeVatCodeFromString, setAllowDecimalQuantity, setAllowDecimalQuantityFromString, setAllowDiscountOrPremium, setAllowDiscountOrPremiumFromString,
 * setCode, setName, setOffice, setOfficeFromString, setPercentage, setPercentageFromString, setPerformanceType, setPerformanceTypeFromString, setShortName, setStatus, setStatusFromString, setType, setTypeFromString, setUnitNamePlural, setUnitNameSingular, setVatCode, setVatCodeFromString, addLine, removeLine
 *
 */

/* ArticleLine
 * \PhpTwinfield\ArticleLine
 * Available getters: getFreeText1, getFreeText1ToString, getFreeText2, getFreeText2ToString, getFreetext3, getID, getInUse, getInUseToString, getMessages, getName, getResult, getShortName, getStatus, getSubCode, getUnits, getUnitsPriceExcl, getUnitsPriceExclToFloat, getUnitsPriceInc, getUnitsPriceIncToFloat, hasMessages
 * Available setters: setFreeText1, setFreeText1FromString, setFreeText2, setFreeText2FromString, setFreetext3, setID, setName, setShortName, setStatus, setStatusFromString, setSubCode, setUnits, setUnitsPriceExcl, setUnitsPriceExclFromFloat, setUnitsPriceInc, setUnitsPriceIncFromFloat
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($articles as $key => $article) {
        echo "Article {$key}<br />";
        echo "Code: {$article->getCode()}<br />";
        echo "Name: {$article->getName()}<br /><br />";
    }
}

// Read an Article based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $article = $articleApiConnector->get("9060", $office);
    } catch (ResponseException $e) {
        $article = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($article);
    echo "</pre>";

    echo "Article<br />";
    echo "AllowChangePerformanceType (bool): {$article->getAllowChangePerformanceType()}<br />";                                                    // bool|null                    Is it allowed to change the performance type.
    echo "AllowChangePerformanceType (string): {$article->getAllowChangePerformanceTypeToString()}<br />";                                          // string|null
    echo "AllowChangeUnitsPrice (bool): {$article->getAllowChangeUnitsPrice()}<br />";                                                              // bool|null                    Is it allowed to change the units price.
    echo "AllowChangeUnitsPrice (string): {$article->getAllowChangeUnitsPriceToString()}<br />";                                                    // string|null
    echo "AllowChangeVatCode (bool): {$article->getAllowChangeVatCode()}<br />";                                                                    // bool|null                    Is it allowed to change the VAT.
    echo "AllowChangeVatCode (string): {$article->getAllowChangeVatCodeToString()}<br />";                                                          // string|null
    echo "AllowDecimalQuantity (bool): {$article->getAllowDecimalQuantity()}<br />";                                                                // bool|null                    Are decimals allowed.
    echo "AllowDecimalQuantity (string): {$article->getAllowDecimalQuantityToString()}<br />";                                                      // string|null
    echo "AllowDiscountOrPremium (bool): {$article->getAllowDiscountOrPremium()}<br />";                                                            // bool|null                    Is discount or premium allowed.
    echo "AllowDiscountOrPremium (string): {$article->getAllowDiscountOrPremiumToString()}<br />";                                                  // string|null
    echo "Code: {$article->getCode()}<br />";                                                                                   					// string|null                  Article code.

    if ($article->hasMessages()) {                                                                                              					// bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($article->getMessages(), true) . "<br />";                                                  					// Array|null                   (Error) messages.
    }

    echo "Name: {$article->getName()}<br />";                                                                                   					// string|null                  Article description.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($article->getOffice(), true) . "</pre><br />";                      					// Office|null                  Office code.
    echo "Office (string): {$article->getOfficeToString()}<br />";                                                              					// string|null
    echo "Percentage (bool): {$article->getPercentage()}<br />";                                                                                    // bool|null                    Only available when article type is discount or premium.
    echo "Percentage (string): {$article->getPercentageToString()}<br />";                                                                          // string|null
    echo "PerformanceType: {$article->getPerformanceType()}<br />";                                                                               	// PerformanceType|null         The performance type.
    echo "Result: {$article->getResult()}<br />";                                                                               					// int|null                     Result (0 = error, 1 or empty = success).
    echo "ShortName: {$article->getShortName()}<br />";                                                                         					// string|null                  Short article description.
    echo "Status: {$article->getStatus()}<br />";                                                                               					// Status|null                  Status of the article.
    echo "Type: {$article->getType()}<br />";                                                                                                       // ArticleType|null             Set to normal in case special item is none. Set to either discount or premium in case special item is deduction or premium respectively.
    echo "UnitNamePlural: {$article->getUnitNamePlural()}<br />";                                                                         			// string|null                  Unit name for multiple items.
    echo "UnitNameSingular: {$article->getUnitNameSingular()}<br />";                                                                         		// string|null                  Unit name for a single item.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($article->getVatCode(), true) . "</pre><br />";                                       // VatCode|null                 Default VAT code.
    echo "VatCode (string): {$article->getVatCodeToString()}<br />";                                                                                // string|null

    $articleLines = $article->getLines();                                                                                                           // array|null                   Array of ArticleLine objects.

    foreach ($articleLines as $key => $articleLine) {
        echo "ArticleLine {$key}<br />";

        echo "FreeText1 (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($articleLine->getFreeText1(), true) . "</pre><br />";                     // GeneralLedger|null           Mandatory. The general ledger code linked to the article.
        echo "FreeText1 (string): {$articleLine->getFreeText1ToString()}<br />";                                                                    // string|null
        echo "FreeText2 (\\PhpTwinfield\\CostCenter): <pre>" . print_r($articleLine->getFreeText2(), true) . "</pre><br />";                        // CostCenter|null              Optional. The cost center linked to the article.
        echo "FreeText2 (string): {$articleLine->getFreeText2ToString()}<br />";                                                                    // string|null
        echo "FreeText3: {$articleLine->getFreetext3()}<br />";                                                                                     // string|null                  Free text element 3
        echo "ID: {$articleLine->getID()}<br />";                                                                                                   // int|null                     Line ID
        echo "InUse (bool): {$articleLine->getInUse()}<br />";                                                                                      // bool|null                    Read-only attribute. Indicates that the sub item has been used in an invoice.
        echo "InUse (string): {$articleLine->getInUseToString()}<br />";                                                                            // string|null

        if ($articleLine->hasMessages()) {                                                                                					        // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($articleLine->getMessages(), true) . "<br />";                                    					        // Array|null                   (Error) messages.
        }

        echo "Name: {$articleLine->getName()}<br />";                                                                                   			// string|null                  Sub article name.
        echo "Result: {$articleLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "ShortName: {$articleLine->getShortName()}<br />";                                                                         			// string|null                  Sub article short name.
        echo "Status: {$articleLine->getStatus()}<br />";                                                                               			// Status|null                  Status of the sub article.
        echo "SubCode: {$articleLine->getSubCode()}<br />";                                                                                   		// string|null                  Can only be empty if there is just one sub article
        echo "Units: {$articleLine->getUnits()}<br />";                                                                               			    // int|null                     The number of units of the article per quantity
        echo "UnitsPriceExcl (\\Money\\Money): <pre>" . print_r($articleLine->getUnitsPriceExcl(), true) . "</pre><br />";                          // Money|null                   Price excluding VAT
        echo "UnitsPriceExcl (float): {$articleLine->getUnitsPriceExclToFloat()}<br />";                                                            // float|null
        echo "UnitsPriceInc (\\Money\\Money): <pre>" . print_r($articleLine->getUnitsPriceInc(), true) . "</pre><br />";                            // Money|null                   Price including VAT
        echo "UnitsPriceInc (float): {$articleLine->getUnitsPriceIncToFloat()}<br />";                                                              // float|null
    }
}

// Copy an existing Article to a new entity
if ($executeCopy) {
    try {
        $article = $articleApiConnector->get("9060", $office);
    } catch (ResponseException $e) {
        $article = $e->getReturnedObject();
    }

    $article->setCode("9061");

    try {
        $articleCopy = $articleApiConnector->send($article);
    } catch (ResponseException $e) {
        $articleCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($articleCopy);
    echo "</pre>";

    echo "Result of copy process: {$articleCopy->getResult()}<br />";
    echo "Code of copied Article: {$articleCopy->getCode()}<br />";
}

// Create a new Article from scratch, alternatively read an existing Article as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $article = new \PhpTwinfield\Article;

    // Required values for creating a new Article
    $article->setCode('9061');                                                                                                                      // string|null                  Article code.
    $article->setName("Example Article");                                                                                                           // string|null                  Article description.
    $article->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $article->setOfficeFromString($officeCode);                                                                                                     // string|null
    //$article->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                    // Status|null                  For creating and updating status may be left empty. For deleting deleted should be used.
    //$article->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                   // Status|null                  In case an article is in use, its status has been changed into hide. Hidden articles can be activated by using active.
    //$article->setStatusFromString('active');                                                                                                      // string|null
    //$article->setStatusFromString('deleted');                                                                                                     // string|null
    $article->setUnitNamePlural("Example Art Units");                                                                                               // string|null                  Unit name for multiple items
    $article->setUnitNameSingular("Example Art Unit");                                                                                              // string|null                  Unit name for a single item

    // Optional values for creating a new Article
    $article->setAllowChangePerformanceType(true);                                                                                                  // bool|null                    Is it allowed to change the performance type.
    $article->setAllowChangePerformanceTypeFromString('true');                                                                                      // string|null
    $article->setAllowChangeUnitsPrice(true);                                                                                                       // bool|null                    Is it allowed to change the units price.
    $article->setAllowChangeUnitsPriceFromString('true');                                                                                           // string|null
    $article->setAllowChangeVatCode(true);                                                                                                          // bool|null                    Is it allowed to change the VAT.
    $article->setAllowChangeVatCodeFromString('true');                                                                                              // string|null
    $article->setAllowDecimalQuantity(true);                                                                                                        // bool|null                    Are decimals allowed.
    $article->setAllowDecimalQuantityFromString('true');                                                                                            // string|null
    $article->setAllowDiscountOrPremium(true);                                                                                                      // bool|null                    Is discount or premium allowed.
    $article->setAllowDiscountOrPremiumFromString('true');                                                                                          // string|null
    //$article->setPercentage(true);                                                                                                                // bool|null                    Only available when article type is discount or premium
    //$article->setPercentageFromString('true');                                                                                                    // string|null
    $article->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                                  // PerformanceType|null         The performance type.
    $article->setPerformanceTypeFromString('services');                                                                                             // string|null
    $article->setShortName("ExmplArt");                                                                                                             // string|null                  Short article description.
    $article->setType(\PhpTwinfield\Enums\ArticleType::NORMAL());                                                                                   // ArticleType|null             Set to normal in case special item is none. Set to either discount or premium in case special item is deduction or premium respectively.
    $article->setTypeFromString('normal');                                                                                                          // string|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $article->setVatCode($vatCode);                                                                                                                 // VatCode|null                 Default VAT code
    $article->setVatCodeFromString('VH');                                                                                                           // string|null

    // The minimum amount of ArticleLines linked to an Article object is 1
    $articleLine = new \PhpTwinfield\ArticleLine;

    $freeText1 = new \PhpTwinfield\GeneralLedger;
    $freeText1->setCode('9060');
    $articleLine->setFreeText1($freeText1);                      		                                                                            // GeneralLedger|null           Mandatory. The general ledger code linked to the article.
    $articleLine->setFreeText1FromString('9060');                                                       	                                        // string|null
    $freeText2 = new \PhpTwinfield\CostCenter;
    $freeText2->setCode('00000');
    //$articleLine->setFreeText2($freeText1);                      		                                                                            // CostCenter|null              Mandatory. The general ledger code linked to the article.
    //$articleLine->setFreeText2FromString('00000');                                                       	                                        // string|null
    //$articleLine->setFreetext3("");                                                                                                               // string|null                  Free text element 3
    $articleLine->setID(1);                                                                                                                         // int|null                     Line ID.
    $articleLine->setName("Example Sub Article");                                                                                                   // string|null                  Sub article name.
    $articleLine->setShortName("ExmplSubArt");                                                                                                      // string|null                  Sub article short name.

    $articleLine->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                  // Status|null                  Allows you to delete sub items and to recover them (if sub item is @inuse).
    //$articleLine->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                               // Status|null
    $articleLine->setStatusFromString('active');                                                                                                    // string|null
    //$articleLine->setStatusFromString('deleted');                                                                                                 // string|null
    $articleLine->setSubCode('9061');                                                                                                               // string|null                  Can only be empty if there is just one sub article
    $articleLine->setUnits(1);                                                                                                                      // int|null                     The number of units of the article per quantity
    $articleLine->setUnitsPriceExcl(\Money\Money::EUR(1000));                                                                                       // Money|null                   Price excluding VAT
    $articleLine->setUnitsPriceExclFromFloat(10);                                                                                                   // float|null
    //$articleLine->setUnitsPriceInc(\Money\Money::EUR(1210));                                                                                      // Money|null                   Price including VAT
    //$articleLine->setUnitsPriceIncFromFloat(12.10);                                                                                               // float|null

    $article->addLine($articleLine);                                                                                                                // ArticleLine                  Add an ArticleLine object to the Article object
    //$article->removeLine(1);                                                                                                                      // int                          Remove an article line based on the id of the article line

    try {
        $articleNew = $articleApiConnector->send($article);
    } catch (ResponseException $e) {
        $articleNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($articleNew);
    echo "</pre>";

    echo "Result of creation process: {$articleNew->getResult()}<br />";
    echo "Code of new Article: {$articleNew->getCode()}<br />";
}

// Delete a Article based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $articleDeleted = $articleApiConnector->delete("9061", $office);
    } catch (ResponseException $e) {
        $articleDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($articleDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$articleDeleted->getResult()}<br />";
}