<?php

/* Supplier
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Purchase/ClassicSuppliers
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers
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

/* Supplier API Connector
 * \PhpTwinfield\ApiConnectors\SupplierApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$supplierApiConnector = new \PhpTwinfield\ApiConnectors\SupplierApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all suppliers
 * @param string $pattern  The search pattern. May contain wildcards * and ?
 * @param int    $field    The search field determines which field or fields will be searched. The available fields
 *                         depends on the finder type. Passing a value outside the specified values will cause an
 *                         error. 0 searches on the code or name field, 1 searches only on the code field,
 *                         2 searches only on the name field, 3 searches only in the dimensions bank account number field,
 *                         4 searches in the dimensions address fields
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
 *                         Usage:                  $options['dimtype'] = 'CRD';
 *
 *                         modifiedsince	       Restricts to dimensions modified after or at the specified date (and time), format yyyyMMddHHmmss or yyyyMMdd
 *                         Usage:                  $options['modifiedsince'] = '20190101170000' or $options['modifiedsince'] = '20190101';
 *
 *                         group	               Specifies the dimension group (wildcards allowed).
 *                         Usage:                  $options['group'] = 'DIMGROUP';
 *
 *                         matchtype    	       This option will show only the relations (dimension type: DEB and/or CRD).
 *                         Usage:                  $options['matchtype'] = 'relation';
 *
 */

//List all with pattern "2*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> matchtype = 'relation'
if ($executeListAllWithFilter) {
    $options = array('matchtype' => 'relation');

    try {
        $suppliers = $supplierApiConnector->listAll("2*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $suppliers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($suppliers);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $suppliers = $supplierApiConnector->listAll();
    } catch (ResponseException $e) {
        $suppliers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($suppliers);
    echo "</pre>";
}

/* Supplier
 * \PhpTwinfield\Supplier
 * Available getters: getBeginPeriod, getBeginYear, getBehaviour, getBlockedAccountPaymentConditionsIncludeVat, getBlockedAccountPaymentConditionsPercentage, getCode, getEndPeriod, getEndYear, getGroup, getInUse, getMessages, getName, getOffice,
 * getPaymentConditionDiscountDays, getPaymentConditionDiscountPercentage, getRemittanceAdviceSendMail, getRemittanceAdviceSendType, getResult, getShortName, getStatus, getTouched, getType, getUID, getWebsite, hasMessages, getAddresses, getBanks, getFinancials, getPostingRules
 *
 * Available setters: setBeginPeriod, setBeginYear, setBehaviour, setBlockedAccountPaymentConditionsIncludeVat, setBlockedAccountPaymentConditionsPercentage, setCode, setEndPeriod, setEndYear, setGroup, setName, setOffice,
 * setPaymentConditionDiscountDays, setPaymentConditionDiscountPercentage, setRemittanceAdviceSendMail, setRemittanceAdviceSendType, setShortName, setStatus, setType, setWebsite, setFinancials, addAddress, removeAddress, addBank, removeBank, addPostingRule, removePostingRule
 *
 */

/* SupplierFinancials
 * \PhpTwinfield\SupplierFinancials
 * Available getters: getAccountType, getDueDays, getLevel, getMatchType, getMeansOfPayment, getMessages, getPayAvailable, getPayCode, getPayCodeID, getRelationsReference, getResult, getSubAnalyse, getSubstituteWith, getSubstituteWithID, getSubstitutionLevel, getVatCode, getVatCodeFixed, getChildValidations, hasMessages
 *
 * Available setters: setAccountType, setDueDays, setLevel, setMatchType, setMeansOfPayment, setPayAvailable, setPayCode, setPayCodeID, setRelationsReference, setSubAnalyse, setSubstituteWith, setSubstituteWithID, setSubstitutionLevel, setVatCode, setVatCodeFixed, addChildValidation, removeChildValidation
 *
 */

/* SupplierChildValidation
 * \PhpTwinfield\SupplierChildValidation
 * Available getters: getElementValue, getLevel, getMessages, getResult, getType, hasMessages
 * Available setters: setElementValue, setLevel, setType
 */

/* SupplierAddress
 * \PhpTwinfield\SupplierAddress
 * Available getters: getCity, getCountry, getDefault, getEmail, getField1, getField2, getField3, getField4, getField5, getField6, getID, getMessages, getName, getPostcode, getResult, getTelefax, getTelephone, getType, hasMessages
 * Available setters: setCity, setCountry, setDefault, setEmail, setField1, setField2, setField3, setField4, setField5, setField6, setID, setName, setPostcode, setTelefax, setTelephone, setType
 */

/* SupplierBank
 * \PhpTwinfield\SupplierBank
 * Available getters: getAccountNumber, getAddressField2, getAddressField3, getAscription, getBankName, getBicCode, getBlocked, getCity, getCountry, getDefault, getID, getIban, getMessages, getNatBicCode, getPostcode, getResult, getState, hasMessages
 * Available setters: setAccountNumber, setAddressField2, setAddressField3, setAscription, setBankName, setBicCode, setBlocked, setCity, setCountry, setDefault, setID, setIban, setNatBicCode, setPostcode, setState
 */

/* SupplierPostingRule
 * \PhpTwinfield\SupplierPostingRule
 * Available getters: getAmount, getCurrency, getDescription, getID, getMessages, getResult, getStatus, getLines, hasMessages
 * Available setters: setAmount, setCurrency, setDescription, setID, setStatus, addLine, removeLine
 */

/* SupplierLine
 * \PhpTwinfield\SupplierLine
 * Available getters: getDescription, getDimension1, getDimension1ID, getDimension2, getDimension2ID, getDimension3, getDimension3ID, getMessages, getOffice, getRatio, getResult, getVatCode, hasMessages
 * Available setters: setDescription, setDimension1, setDimension1ID, setDimension2, setDimension2ID, setDimension3, setDimension3ID, setOffice, setRatio, setVatCode
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($suppliers as $key => $supplier) {
        echo "Supplier {$key}<br />";
        echo "Code: {$supplier->getCode()}<br />";
        echo "Name: {$supplier->getName()}<br /><br />";
    }
}

// Read a Supplier based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $supplier = $supplierApiConnector->get("2000", $office);
    } catch (ResponseException $e) {
        $supplier = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($supplier);
    echo "</pre>";

    echo "Supplier<br />";
    echo "BeginPeriod: {$supplier->getBeginPeriod()}<br />";                                                                               			// int|null                         Determines together with beginyear the period from which the dimension may be used.
    echo "BeginYear: {$supplier->getBeginYear()}<br />";                                                                               			    // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    echo "Behaviour: {$supplier->getBehaviour()}<br />";                                                                                   		    // Behaviour|null                   Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$supplier->getCode()}<br />";                                                                                   					// string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.
    echo "EndPeriod: {$supplier->getEndPeriod()}<br />";                                                                               			    // int|null                         Determines together with endyear the period till which the dimension may be used.
    echo "EndYear: {$supplier->getEndYear()}<br />";                                                                               			        // int|null                         Determines together with endperiod the period till which the dimension may be used.
    echo "Group (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($supplier->getGroup(), true) . "</pre><br />";                      			    // DimensionGroup|null              Sets the dimension group. See Dimension group.
    echo "Group (string): {$supplier->getGroupToString()}<br />";                                                              					    // string|null
    echo "InUse (bool): {$supplier->getInUse()}<br />";                                                                                   			// bool|null                        Indicates whether the balancesheet is used in a financial transaction or not. Read-only attribute.
    echo "InUse (string): {$supplier->getInUseToString()}<br />";                                                                                   // string|null

    if ($supplier->hasMessages()) {                                                                                              					// bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($supplier->getMessages(), true) . "<br />";                                                  					// Array|null                       (Error) messages.
    }

    echo "Name: {$supplier->getName()}<br />";                                                                                   					// string|null                      Name of the dimension.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($supplier->getOffice(), true) . "</pre><br />";                      					// Office|null                      Office code.
    echo "Office (string): {$supplier->getOfficeToString()}<br />";                                                              					// string|null
    echo "PaymentCondition:<br />";                                                                                                                 //                                  Sets the payment condition of a dimension.
    echo "PaymentCondition DiscountDays: {$supplier->getPaymentConditionDiscountDays()}<br />";                                                     // int|null                         Number of discount days.
    echo "PaymentCondition DiscountPercentage: {$supplier->getPaymentConditionDiscountPercentage()}<br />";                                         // float|null                       Discount percentage.
    echo "RemittanceAdvice:<br />";                                                                                                                 //
    echo "RemittanceAdvice SendMail: {$supplier->getRemittanceAdviceSendMail()}<br />";                                                             // string|null                      Mandatory if sendtype = ByEmail, remittance advice will be sent using this e-mail address.
    echo "RemittanceAdvice SendType: {$supplier->getRemittanceAdviceSendType()}<br />";                                                             // RemittanceAdviceSendType|null    To file manager, By e-mail
    echo "Result: {$supplier->getResult()}<br />";                                                                               					// int|null                         Result (0 = error, 1 or empty = success).
    echo "ShortName: {$supplier->getShortName()}<br />";                                                                         					// string|null                      Short name of the dimension.
    echo "Status: {$supplier->getStatus()}<br />";                                                                               					// Status|null                      Status of the supplier.
    echo "Touched: {$supplier->getTouched()}<br />";                                                                                                // int|null                         Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($supplier->getType(), true) . "</pre><br />";                                      // DimensionType|null               Dimension type. See Dimension type. Dimension type of suppliers is DEB.
    echo "Type (string): {$supplier->getTypeToString()}<br />";                                                                                     // string|null
    echo "UID: {$supplier->getUID()}<br />";                                                                                                        // string|null                      Unique identification of the dimension. Read-only attribute.
    echo "Website: {$supplier->getWebsite()}<br />";                                                                                                // string|null                      Website of the dimension.

    $supplierAddresses = $supplier->getAddresses();                                                                                                 // array|null                       Array of SupplierAddress objects.

    foreach ($supplierAddresses as $key => $supplierAddress) {
        echo "SupplierAddress {$key}<br />";

        echo "City: {$supplierAddress->getCity()}<br />";                                                                                           // string|null                      City.
        echo "Country (\\PhpTwinfield\\Country): <pre>" . print_r($supplierAddress->getCountry(), true) . "</pre><br />";                      		// Country|null                     Country code. The ISO country codes are used.
        echo "Country (string): {$supplierAddress->getCountryToString()}<br />";                                                              		// string|null
        echo "Default (bool): {$supplierAddress->getDefault()}<br />";                                                                              // bool|null                        Is this the default address, only one default address is possible.
        echo "Default (string): {$supplierAddress->getDefaultToString()}<br />";                                                                    // string|null
        echo "Email: {$supplierAddress->getEmail()}<br />";                                                                                         // string|null
        echo "Field1: {$supplierAddress->getField1()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field2: {$supplierAddress->getField2()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field3: {$supplierAddress->getField3()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field4: {$supplierAddress->getField4()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type. Currently, field4 is reserved for VAT numbers. So only valid VAT numbers may be filled in.
        echo "Field5: {$supplierAddress->getField5()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field6: {$supplierAddress->getField6()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "ID: {$supplierAddress->getID()}<br />";                                                                                               // int|null                         Sequence number of the address line.

        if ($supplierAddress->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($supplierAddress->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
        }

        echo "Name: {$supplierAddress->getName()}<br />";                                                                    			            // string|null                     	Company name.
        echo "Postcode: {$supplierAddress->getPostcode()}<br />";                                                                                   // string|null                      Postcode.
        echo "Result: {$supplierAddress->getResult()}<br />";                                                                                       // int|null                         Result (0 = error, 1 or empty = success).
        echo "Telefax: {$supplierAddress->getTelefax()}<br />";                                                                    			        // string|null                     	Fax number.
        echo "Telephone: {$supplierAddress->getTelephone()}<br />";                                                                    			    // string|null                     	Telephone number.
        echo "Type: {$supplierAddress->getType()}<br />";                                                                                           // AddressType|null                 The type of the address.
    }

    $supplierBanks = $supplier->getBanks();

    foreach ($supplierBanks as $key => $supplierBank) {
        echo "SupplierBank {$key}<br />";

        echo "AccountNumber: {$supplierBank->getAccountNumber()}<br />";                                                                            // string|null                      Account number.
        echo "AddressField2: {$supplierBank->getAddressField2()}<br />";                                                                            // string|null                      Bank address.
        echo "AddressField3: {$supplierBank->getAddressField3()}<br />";                                                                            // string|null                      Bank address number.
        echo "Ascription: {$supplierBank->getAscription()}<br />";                                                                                  // string|null                      Account holder.
        echo "BankName: {$supplierBank->getBankName()}<br />";                                                                                      // string|null                      Bank name.
        echo "BicCode: {$supplierBank->getBicCode()}<br />";                                                                                        // string|null                      BIC code.

        echo "Blocked (bool): {$supplierBank->getBlocked()}<br />";                                                                                 // bool|null
        echo "Blocked (string): {$supplierBank->getBlockedToString()}<br />";                                                                       // string|null
        echo "City: {$supplierBank->getCity()}<br />";                                                                                              // string|null                      City.
        echo "Country (\\PhpTwinfield\\Country): <pre>" . print_r($supplierBank->getCountry(), true) . "</pre><br />";                      		// Country|null                     Bank country code. The ISO country codes are used.
        echo "Country (string): {$supplierBank->getCountryToString()}<br />";                                                              		    // string|null
        echo "Default (bool): {$supplierBank->getDefault()}<br />";                                                                                 // bool|null                        Is this the default bank account, only one default bank account is possible.
        echo "Default (string): {$supplierBank->getDefaultToString()}<br />";                                                                       // string|null
        echo "ID: {$supplierBank->getID()}<br />";                                                                                                  // int|null                         Sequence number of the bank account line. When adding a new bank, do not supply the @id. When changing a bank account, supply the corresponding @id.
        echo "IBAN: {$supplierBank->getIban()}<br />";                                                                                              // string|null                      IBAN account number.

        if ($supplierBank->hasMessages()) {                                                                                					        // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($supplierBank->getMessages(), true) . "<br />";                                    					        // Array|null                       (Error) messages.
        }

        echo "NatBicCode: {$supplierBank->getNatBicCode()}<br />";                                                                    			    // string|null              	    National bank code.
        echo "Postcode: {$supplierBank->getPostcode()}<br />";                                                                                      // string|null                      Postcode.
        echo "Result: {$supplierBank->getResult()}<br />";                                                                                          // int|null                         Result (0 = error, 1 or empty = success).
        echo "State: {$supplierBank->getState()}<br />";                                                                    			            // string|null                     	State.
    }

    echo "SupplierFinancials<br />";
    $supplierFinancials = $supplier->getFinancials();                                                                           			        // SupplierFinancials|null          SupplierFinancials object.

    echo "AccountType: {$supplierFinancials->getAccountType()}<br />";                                                                              // AccountType|null                 Fixed value inherit.
    echo "DueDays: {$supplierFinancials->getDueDays()}<br />";                                                                                      // int|null                         The number of due days.
    echo "Level: {$supplierFinancials->getLevel()}<br />";                                                                                          // int|null                        	Specifies the dimension level. Normally the level of suppliers is level 2. Read-only attribute.
    echo "MatchType: {$supplierFinancials->getMatchType()}<br />";                                                                                  // MatchType|null                   Fixed value customersupplier.
    echo "MeansOfPayment: {$supplierFinancials->getMeansOfPayment()}<br />";                                                                        // MeansOfPayment|null              The option none is only allowed in case payavailable is set to false. The option paymentfile is only allowed in case payavailable is set to true.

    if ($supplierFinancials->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($supplierFinancials->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
    }

    echo "PayAvailable (bool): {$supplierFinancials->getPayAvailable()}<br />";                                                                     // bool|null                        Determines if direct debit is possible.
    echo "PayAvailable (string): {$supplierFinancials->getPayAvailableToString()}<br />";                                                           // string|null
    echo "PayCode (\\PhpTwinfield\\PayCode): <pre>" . print_r($supplierFinancials->getPayCode(), true) . "</pre><br />";                            // PayCode|null                     The code of the payment type in case direct debit is possible.
    echo "PayCode (string): {$supplierFinancials->getPayCodeToString()}<br />";                                                                     // string|null
    echo "PayCodeID: {$supplierFinancials->getPayCodeID()}<br />";                                                                                  // string|null
    echo "RelationsReference: {$supplierFinancials->getRelationsReference()}<br />";                                                                // string|null                      External supplier number.
    echo "Result: {$supplierFinancials->getResult()}<br />";                                                                                        // int|null                         Result (0 = error, 1 or empty = success).
    echo "SubAnalyse: {$supplierFinancials->getSubAnalyse()}<br />";                                                                                // SubAnalyse|null                  Fixed value false.
    echo "SubstituteWith (\\PhpTwinfield\\Dummy): <pre>" . print_r($supplierFinancials->getSubstituteWith(), true) . "</pre><br />";                // Dummy|null                       Default supplier balancesheet account.
    echo "SubstituteWith (string): {$supplierFinancials->getSubstituteWithToString()}<br />";                                                       // string|null
    echo "SubstituteWithID: {$supplierFinancials->getSubstituteWithID()}<br />";                                                                    // string|null
    echo "SubstitutionLevel: {$supplierFinancials->getSubstitutionLevel()}<br />";                                                                  // int|null                         Level of the balancesheet account. Fixed value 1.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($supplierFinancials->getVatCode(), true) . "</pre><br />";                            // VatCode|null                     Default VAT code.
    echo "VatCode (string): {$supplierFinancials->getVatCodeToString()}<br />";                                                                     // string|null
    echo "VatCode Fixed (bool): {$supplierFinancials->getVatCodeFixed()}<br />";                                                                    // bool|null
    echo "VatCode Fixed (string): {$supplierFinancials->getVatCodeFixedToString()}<br />";                                                          // string|null

    $supplierChildValidations = $supplierFinancials->getChildValidations();                                                                         // array|null                       Array of SupplierChildValidations objects.

    foreach ($supplierChildValidations as $key => $supplierChildValidation) {
        echo "SupplierChildValidation {$key}<br />";

        echo "ElementValue: {$supplierChildValidation->getElementValue()}<br />";                                                                   // string|null
        echo "Level: {$supplierChildValidation->getLevel()}<br />";                                                                                 // int|null

        if ($supplierChildValidation->hasMessages()) {                                                                                				// bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($supplierChildValidation->getMessages(), true) . "<br />";                                    				// Array|null                       (Error) messages.
        }

        echo "Result: {$supplierChildValidation->getResult()}<br />";                                                                               // int|null                         Result (0 = error, 1 or empty = success).
        echo "Type: {$supplierChildValidation->getType()}<br />";                                                                                   // ChildValidationType|null
    }

    $supplierPostingRules = $supplier->getPostingRules();                                                                                           // array|null                       Array of SupplierPostingRule objects.

    foreach ($supplierPostingRules as $key => $supplierPostingRule) {
        echo "SupplierPostingRule {$key}<br />";

        echo "Amount (\\Money\\Money): <pre>" . print_r($supplierPostingRule->getAmount(), true) . "</pre><br />";                                  // Money|null                       Amount.
        echo "Amount (float): {$supplierPostingRule->getAmountToFloat()}<br />";                                                                    // float|null
        echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($supplierPostingRule->getCurrency(), true) . "</pre><br />";                    // Currency|null                    Currency.
        echo "Currency (string): {$supplierPostingRule->getCurrencyToString()}<br />";                                                              // string|null
        echo "Description: {$supplierPostingRule->getDescription()}<br />";                                                                         // string|null                      Description.
        echo "ID: {$supplierPostingRule->getID()}<br />";                                                                                           // int|null                         Sequence number of the posting rule. Fixed value 1.

        if ($supplierPostingRule->hasMessages()) {                                                                                					// bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($supplierPostingRule->getMessages(), true) . "<br />";                                    					// Array|null                       (Error) messages.
        }

        echo "Result: {$supplierPostingRule->getResult()}<br />";                                                                                   // int|null                         Result (0 = error, 1 or empty = success).
        echo "Status: {$supplierPostingRule->getStatus()}<br />";                                                                               	// Status|null                      Status of the posting rule.

        $supplierLines = $supplierPostingRule->getLines();                                                                                          // array|null                       Array of SupplierLine objects.

        foreach ($supplierLines as $key => $supplierLine) {
            echo "SupplierLine {$key}<br />";

            echo "Description: {$supplierLine->getDescription()}<br />";                                                                            // string|null                      Description.
            echo "Dimension1 (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($supplierLine->getDimension1(), true) . "</pre><br />";              // GeneralLedger|null               General ledger.
            echo "Dimension1 (string): {$supplierLine->getDimension1ToString()}<br />";                                                             // string|null
            echo "Dimension1ID: {$supplierLine->getDimension1ID()}<br />";                                                                          // string|null
            echo "Dimension2 (\\PhpTwinfield\\CostCenter): <pre>" . print_r($supplierLine->getDimension2(), true) . "</pre><br />";                 // CostCenter|null                  Cost center.
            echo "Dimension2 (string): {$supplierLine->getDimension2ToString()}<br />";                                                             // string|null
            echo "Dimension2ID: {$supplierLine->getDimension2ID()}<br />";                                                                          // string|null
            echo "Dimension3 (\\PhpTwinfield\\Dummy): <pre>" . print_r($supplierLine->getDimension3(), true) . "</pre><br />";                      // Dummy|null                       Project or asset.
            echo "Dimension3 (string): {$supplierLine->getDimension3ToString()}<br />";                                                             // string|null
            echo "Dimension3ID: {$supplierLine->getDimension3ID()}<br />";                                                                          // string|null

            if ($supplierLine->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
                echo "Messages: " . print_r($supplierLine->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
            }

            echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($supplierLine->getOffice(), true) . "</pre><br />";                      		// Office|null                      Destination company.
            echo "Office (string): {$supplierLine->getOfficeToString()}<br />";                                                              		// string|null
            echo "Ratio: {$supplierLine->getRatio()}<br />";                                                                                        // float|null                       The ratio of the posting rule line.
            echo "Result: {$supplierLine->getResult()}<br />";                                                                                      // int|null                         Result (0 = error, 1 or empty = success).
            echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($supplierLine->getVatCode(), true) . "</pre><br />";                          // VatCode|null                     VAT code.
            echo "VatCode (string): {$supplierLine->getVatCodeToString()}<br />";                                                                   // string|null
        }
    }
}

// Copy an existing Supplier to a new entity
if ($executeCopy) {
    try {
        $supplier = $supplierApiConnector->get("2000", $office);
    } catch (ResponseException $e) {
        $supplier = $e->getReturnedObject();
    }

    $supplier->setCode(null);                                                                                                                       // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$supplier->setCode('2100');                                                                                                                   // string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.

    //Twinfield does not accept BankID's on new entities
    $supplierBanks = $supplier->getBanks();

    foreach ($supplierBanks as $supplierBank) {
        $supplierBank->setID(null);
    }

    try {
        $supplierCopy = $supplierApiConnector->send($supplier);
    } catch (ResponseException $e) {
        $supplierCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($supplierCopy);
    echo "</pre>";

    echo "Result of copy process: {$supplierCopy->getResult()}<br />";
    echo "Code of copied Supplier: {$supplierCopy->getCode()}<br />";
}

// Create a new Supplier from scratch, alternatively read an existing Supplier as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $supplier = new \PhpTwinfield\Supplier;

    // Required values for creating a new Supplier
    $supplier->setCode(null);                                                                                                                       // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$supplier->setCode('2100');                                                                                                                   // string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.
    $supplier->setName("Example Supplier");                                                                                                         // string|null                      Name of the dimension.
    $supplier->setOffice($office);                                                                                                                  // Office|null                      Office code.
    $supplier->setOfficeFromString($officeCode);                                                                                                    // string|null

    // Optional values for creating a new Supplier
    $supplier->setBeginPeriod(0);                                                                                                                   // int|null                         Determines together with beginyear the period from which the dimension may be used.
    $supplier->setBeginYear(0);                                                                                                                     // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    $supplier->setEndPeriod(0);                                                                                                                     // int|null                         Determines together with endyear the period till which the dimension may be used.
    $supplier->setEndYear(0);                                                                                                                       // int|null                         Determines together with endperiod the period till which the dimension may be used.
    $supplier->setShortName("ExmplCust");                                                                                                           // string|null                      Short name of the dimension.
    //$supplier->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                   // Status|null                      For creating and updating status may be left empty. For deleting deleted should be used.
    //$supplier->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                  // Status|null                      In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.
    //$supplier->setStatusFromString('active');                                                                                                     // string|null
    //$supplier->setStatusFromString('deleted');                                                                                                    // string|null
    $supplier->setWebsite("www.example.com");                                                                                                       // string|null                      Website of the dimension.

    $dimensionGroup = new \PhpTwinfield\DimensionGroup;
    $dimensionGroup->setCode('DIMGROUP');
    //$supplier->setGroup($dimensionGroup);                                                                                                         // DimensionGroup|null              Sets the dimension group. See Dimension group.
    //$supplier->setGroupFromString("DIMGROUP");                                                                                                    // string|null

    $supplier->setPaymentConditionDiscountDays(3);                                                                                                  // int|null                         Number of discount days.
    $supplier->setPaymentConditionDiscountPercentage(25);                                                                                           // int|null                         Discount percentage.

    $supplier->setRemittanceAdviceSendMail("test@example.com");                                                                                     // string|null                      Mandatory if sendtype = ByEmail, remittance advice will be sent using this e-mail address.
    $supplier->setRemittanceAdviceSendType(\PhpTwinfield\Enums\RemittanceAdviceSendType::BYEMAIL());                                                // RemittanceAdviceSendMail|null    To file manager, By e-mail
    $supplier->setRemittanceAdviceSendTypeFromString('ByEmail');                                                                                    // string|null

    $supplierFinancials = new \PhpTwinfield\SupplierFinancials;
    $supplierFinancials->setDueDays(14);                                                                                                            // int|null                         The number of due days.
    $supplierFinancials->setMeansOfPayment(\PhpTwinfield\Enums\MeansOfPayment::PAYMENTFILE());                                                      // MeansOfPayment|null              The option none is only allowed in case payavailable is set to false. The option paymentfile is only allowed in case payavailable is set to true.
    $supplierFinancials->setMeansOfPaymentFromString('paymentfile');                                                                                // string|null
    $supplierFinancials->setPayAvailable(true);                                                                                                     // bool|null                        Determines if direct debit is possible.
    $supplierFinancials->setPayAvailableFromString('true');                                                                                         // string|null
    $payCode = new \PhpTwinfield\PayCode;
    $payCode->setCode('SEPANLCT');
    $supplierFinancials->setPayCode($payCode);                                                                                                      // PayCode|null                     The code of the payment type in case direct debit is possible.
    $supplierFinancials->setPayCodeFromString('SEPANLCT');                                                                                          // string|null
    $substituteWith = new \PhpTwinfield\GeneralLedger;
    $substituteWith->getCode('1535');
    $supplierFinancials->setSubstituteWith($substituteWith);                                                                                        // GeneralLedger|null               Default supplier balancesheet account.
    $supplierFinancials->setSubstituteWithFromString('1535');                                                                                       // string|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('IH');
    $supplierFinancials->setVatCode($vatCode);                                                                                                      // VatCode|null                     Default VAT code.
    $supplierFinancials->setVatCodeFromString('IH');                                                                                                // string|null

    $supplier->setFinancials($supplierFinancials);                                                                                                  // SupplierFinancials               Set the SupplierFinancials object tot the Supplier object

    $supplierAddress = new \PhpTwinfield\SupplierAddress;
    $supplierAddress->setCity('Amsterdam');                                                                                                         // string|null                      City.
    $country = new \PhpTwinfield\Country;
    $country->setCode('NL');
    $supplierAddress->setCountry($country);                                                                                                         // Country|null                     Country code. The ISO country codes are used.
    $supplierAddress->setCountryFromString('NL');                                                                                                   // string|null
    $supplierAddress->setDefault(true);                                                                                                             // bool|null                       	Is this the default address, only one default address is possible.
    $supplierAddress->setDefaultFromString('true');                                                                                                 // string|null
    $supplierAddress->setEmail('test@example.com');                                                                                                 // string|null
    $supplierAddress->setField1('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $supplierAddress->setField2('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $supplierAddress->setField3('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $supplierAddress->setField4(null);                                                                                                              // string|null                      User defined fields, the labels are configured in the Dimension type. Currently, field4 is reserved for VAT numbers. So only valid VAT numbers may be filled in.
    $supplierAddress->setField5('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $supplierAddress->setField6('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $supplierAddress->setID(1);                                                                                                                     // string|null                      Sequence number of the address line.
    $supplierAddress->setName('Example Supplier');                                                                                                  // string|null                      Company name.
    $supplierAddress->setPostcode('9876YZ');                                                                                                        // string|null                      Postcode.
    $supplierAddress->setTelefax('012-3456789');                                                                                                    // string|null                      Fax number.
    $supplierAddress->setTelephone('987-654321');                                                                                                   // string|null                      Telephone number.
    $supplierAddress->setType(\PhpTwinfield\Enums\AddressType::INVOICE());                                                                          // AddressType|null                 The type of the address.
    $supplierAddress->setTypeFromString('invoice');                                                                                                 // string|null

    $supplier->addAddress($supplierAddress);                                                                                                        // SupplierAddress                  Add a SupplierAddress object to the Supplier object
    //$supplier->removeAddress(0);                                                                                                                  // int                              Remove an address based on the index of the address within the array

    $supplierBank = new \PhpTwinfield\SupplierBank;
    $supplierBank->setAccountNumber('123456789');                                                                                                   // string|null                      Account number.
    $supplierBank->setAddressField2('Example Street');                                                                                              // string|null                      Bank address.
    $supplierBank->setAddressField3('12');                                                                                                          // string|null                      Bank address number.
    $supplierBank->setAscription('Example Supplier');                                                                                               // string|null                      Account holder.
    $supplierBank->setBankName('Example Bank');                                                                                                     // string|null                      Bank name.
    $supplierBank->setBicCode('ABNANL2A');                                                                                                          // string|null                      BIC code.
    $supplierBank->setBlocked(false);                                                                                                               // bool|null
    $supplierBank->setBlockedFromString('false');                                                                                                   // string|null
    $supplierBank->setCity('Amsterdam');                                                                                                            // string|null                      City.
    $country = new \PhpTwinfield\Country;
    $country->setCode('NL');
    $supplierBank->setCountry($country);                                                                                                            // Country|null                     Bank country code. The ISO country codes are used.
    $supplierBank->setCountryFromString('NL');                                                                                                      // string|null
    $supplierBank->setDefault(true);                                                                                                                // bool|null                        Is this the default bank account, only one default bank account is possible.
    $supplierBank->setDefaultFromString('true');                                                                                                    // string|null
    $supplierBank->setID(null);                                                                                                                     // int|null                         Sequence number of the bank account line. When adding a new bank, do not supply the @id. When changing a bank account, supply the corresponding @id.
    $supplierBank->setIban(null);                                                                                                                   // string|null                      IBAN account number.
    $supplierBank->setNatBicCode('NL');                                                                                                             // string|null                      National bank code.
    $supplierBank->setPostcode('1234AB');                                                                                                           // string|null                      Postcode.
    $supplierBank->setState('Noord-Holland');                                                                                                       // string|null                      State.

    $supplier->addBank($supplierBank);                                                                                                              // SupplierBank                     Add a SupplierBank object to the Supplier object
    //$supplier->removeBank(0);                                                                                                                     // int                              Remove a bank based on the index of the bank within the array

    $supplierPostingRule = new \PhpTwinfield\SupplierPostingRule;
    $supplierPostingRule->setAmount(\Money\Money::EUR(10000));                                                                                      // Money|null                       Amount.
    $supplierPostingRule->setAmountFromFloat(100);                                                                                                  // float|null
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $supplierPostingRule->setCurrency($currency);                                                                                                   // Currency|null                    Currency.
    $supplierPostingRule->setCurrencyFromString('EUR');                                                                                             // string|null
    $supplierPostingRule->setDescription('Example PostingRule');                                                                                    // string|null                      Description.
    $supplierPostingRule->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                          // Status|null                      For creating and updating active should be used. For deleting deleted should be used.
    //$supplierPostingRule->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                       // Status|null
    $supplierPostingRule->setStatusFromString('active');                                                                                            // string|null
    //$supplierPostingRule->setStatusFromString('deleted');                                                                                         // string|null

    $supplierLine = new \PhpTwinfield\SupplierLine;
    $supplierLine->setDescription('Example Line');                                                                                                  // string|null                      Description.
    $dimension1 = new \PhpTwinfield\GeneralLedger;
    $dimension1->setCode('1535');
    $supplierLine->setDimension1($dimension1);                                                                                                      // GeneralLedger|null               General ledger.
    $supplierLine->setDimension1FromString('1535');                                                                                                 // string|null
    $costCenter = new \PhpTwinfield\CostCenter;
    $costCenter->setCode('00000');
    $supplierLine->setDimension2($costCenter);                                                                                                      // CostCenter|null                  Cost center.
    $supplierLine->setDimension2FromString ('00000');                                                                                               // string|null
    $activity = new \PhpTwinfield\Activity;
    $activity->setCode('P0000');
    $supplierLine->setDimension3($activity);                                                                                                        // Dummy|null                       Project or asset.
    $supplierLine->setDimension3FromString('P0000');                                                                                                // string|null
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('NLA0000001');
    //$supplierLine->setOffice($destOffice);                                                                                                        // Office|null                      Destination company.
    //$supplierLine->setOfficeFromString('NLA0000001');                                                                                             // string|null
    $supplierLine->setRatio(1);                                                                                                                     // float|null                       The ratio of the posting rule line.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('IH');
    $supplierLine->setVatCode($vatCode);                                                                                                            // VatCode|null                     Default VAT code.
    $supplierLine->setVatCodeFromString('IH');                                                                                                      // string|null

    $supplierPostingRule->addLine($supplierLine);                                                                                                   // SupplierLine                     Add a SupplierLine object to the SupplierPostingRule object
    //$supplierPostingRule->removeLine(0);                                                                                                          // int                              Remove a line based on the index of the line within the array

    $supplier->addPostingRule($supplierPostingRule);                                                                                                // SupplierPostingRule              Add a SupplierPostingRule object to the Supplier object
    //$supplier->removePostingRule(0);                                                                                                              // int                              Remove a posting rule based on the index of the posting rule within the array

    try {
        $supplierNew = $supplierApiConnector->send($supplier);
    } catch (ResponseException $e) {
        $supplierNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($supplierNew);
    echo "</pre>";

    echo "Result of creation process: {$supplierNew->getResult()}<br />";
    echo "Code of new Supplier: {$supplierNew->getCode()}<br />";
}

// Delete a Supplier based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $supplierDeleted = $supplierApiConnector->delete("2004", $office);
    } catch (ResponseException $e) {
        $supplierDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($supplierDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$supplierDeleted->getResult()}<br />";
}