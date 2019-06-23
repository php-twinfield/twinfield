<?php

/* Customer
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Sales/ClassicCustomers
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
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

/* Customer API Connector
 * \PhpTwinfield\ApiConnectors\CustomerApiConnector
 * Available methods: delete, get, listAll, send, sendAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$customerApiConnector = new \PhpTwinfield\ApiConnectors\CustomerApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all customers
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
 *                         Usage:                  $options['dimtype'] = 'DEB';
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
        $customers = $customerApiConnector->listAll("2*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $customers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customers);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $customers = $customerApiConnector->listAll();
    } catch (ResponseException $e) {
        $customers = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customers);
    echo "</pre>";
}

/* Customer
 * \PhpTwinfield\Customer
 * Available getters: getBeginPeriod, getBeginYear, getBehaviour, getCode, getDiscountArticle, getDiscountArticleID, getEndPeriod, getEndYear, getGroup, getInUse, getMessages, getName, getOffice,
 * getPaymentConditionDiscountDays, getPaymentConditionDiscountPercentage, getRemittanceAdviceSendMail, getRemittanceAdviceSendType, getResult, getShortName, getStatus, getTouched, getType, getUID, getWebsite, hasMessages, getAddresses, getBanks, getCreditManagement, getFinancials, getPostingRules
 *
 * Available setters: setBeginPeriod, setBeginYear, setBehaviour, setCode, setDiscountArticle, setDiscountArticleID, setEndPeriod, setEndYear, setGroup, setName, setOffice, setPaymentConditionDiscountDays,
 * setPaymentConditionDiscountPercentage, setRemittanceAdviceSendMail, setRemittanceAdviceSendType, setShortName, setStatus, setType, setWebsite, setCreditManagement, setFinancials, addAddress, addBank, addPostingRule, removeAddress, removeBank, removePostingRule
 *
 */

/* CustomerFinancials
 * \PhpTwinfield\CustomerFinancials
 * Available getters: getAccountType, getCollectionSchema, getDueDays, getEBillMail, getEBilling, getLevel, getMatchType, getMeansOfPayment, getMessages, getPayAvailable, getPayCode, getPayCodeID,
 * getResult, getSubAnalyse, getSubstituteWith, getSubstituteWithID, getSubstitutionLevel, getVatCode, getVatCodeFixed, getChildValidations, getCollectMandate, hasMessages
 *
 * Available setters: setAccountType, setCollectionSchema, setDueDays, setEBillMail, setEBilling, setLevel, setMatchType, setMeansOfPayment,
 * setPayAvailable, setPayCode, setPayCodeID, setSubAnalyse, setSubstituteWith, setSubstituteWithID, setSubstitutionLevel, setVatCode, setVatCodeFixed, setCollectMandate, addChildValidation, removeChildValidation
 *
 */

/* CustomerCollectMandate
 * \PhpTwinfield\CustomerCollectMandate
 * Available getters: getFirstRunDate, getID, getMessages, getResult, getSignatureDate, hasMessages
 * Available setters: setFirstRunDate, setID, setSignatureDate
 */

/* CustomerChildValidation
 * \PhpTwinfield\CustomerChildValidation
 * Available getters: getElementValue, getLevel, getMessages, getResult, getType, hasMessages
 * Available setters: setElementValue, setLevel, setType
 */

/* CustomerCreditManagement
 * \PhpTwinfield\CustomerCreditManagement
 * Available getters: getBaseCreditLimit, getBlocked, getBlockedLocked, getBlockedModified, getComment, getFreeText1, getFreetext2, getFreetext3, getMessages, getReminderEmail, getResponsibleUser, getResult, getSendReminder, hasMessages
 * Available setters: setBaseCreditLimit, setBlocked, setBlockedLocked, setBlockedModified, setComment, setFreeText1, setFreetext2, setFreetext3, setReminderEmail, setResponsibleUser, setSendReminder
 */

/* CustomerAddress
 * \PhpTwinfield\CustomerAddress
 * Available getters: getCity, getCountry, getDefault, getEmail, getField1, getField2, getField3, getField4, getField5, getField6, getID, getMessages, getName, getPostcode, getResult, getTelefax, getTelephone, getType, hasMessages
 * Available setters: setCity, setCountry, setDefault, setEmail, setField1, setField2, setField3, setField4, setField5, setField6, setID, setName, setPostcode, setTelefax, setTelephone, setType
 */

/* CustomerBank
 * \PhpTwinfield\CustomerBank
 * Available getters: getAccountNumber, getAddressField2, getAddressField3, getAscription, getBankName, getBicCode, getBlocked, getCity, getCountry, getDefault, getID, getIban, getMessages, getNatBicCode, getPostcode, getResult, getState, hasMessages
 * Available setters: setAccountNumber, setAddressField2, setAddressField3, setAscription, setBankName, setBicCode, setBlocked, setCity, setCountry, setDefault, setID, setIban, setNatBicCode, setPostcode, setState
 */

/* CustomerPostingRule
 * \PhpTwinfield\CustomerPostingRule
 * Available getters: getAmount, getCurrency, getDescription, getID, getMessages, getResult, getStatus, getLines, hasMessages
 * Available setters: setAmount, setCurrency, setDescription, setID, setStatus, addLine, removeLine
 */

/* CustomerLine
 * \PhpTwinfield\CustomerLine
 * Available getters: getDescription, getDimension1, getDimension1ID, getDimension2, getDimension2ID, getDimension3, getDimension3ID, getMessages, getOffice, getRatio, getResult, getVatCode, hasMessages
 * Available setters: setDescription, setDimension1, setDimension1ID, setDimension2, setDimension2ID, setDimension3, setDimension3ID, setOffice, setRatio, setVatCode
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($customers as $key => $customer) {
        echo "Customer {$key}<br />";
        echo "Code: {$customer->getCode()}<br />";
        echo "Name: {$customer->getName()}<br /><br />";
    }
}

// Read a Customer based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $customer = $customerApiConnector->get("1000", $office);
    } catch (ResponseException $e) {
        $customer = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customer);
    echo "</pre>";

    echo "Customer<br />";
    echo "BeginPeriod: {$customer->getBeginPeriod()}<br />";                                                                               			// int|null                         Determines together with beginyear the period from which the dimension may be used.
    echo "BeginYear: {$customer->getBeginYear()}<br />";                                                                               			    // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    echo "Behaviour: {$customer->getBehaviour()}<br />";                                                                                   		    // Behaviour|null                   Determines the behaviour of dimensions. Read-only attribute.
    echo "Code: {$customer->getCode()}<br />";                                                                                   					// string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.
    echo "DiscountArticle (\\PhpTwinfield\\Article): <pre>" . print_r($customer->getDiscountArticle(), true) . "</pre><br />";                      // Article|null                     The discount or premium article.
    echo "DiscountArticle (string): {$customer->getDiscountArticleToString()}<br />";                                                               // string|null
    echo "DiscountArticleID: {$customer->getDiscountArticleID()}<br />";                                                                            // string|null
    echo "EndPeriod: {$customer->getEndPeriod()}<br />";                                                                               			    // int|null                         Determines together with endyear the period till which the dimension may be used.
    echo "EndYear: {$customer->getEndYear()}<br />";                                                                               			        // int|null                         Determines together with endperiod the period till which the dimension may be used.
    echo "Group (\\PhpTwinfield\\DimensionGroup): <pre>" . print_r($customer->getGroup(), true) . "</pre><br />";                      			    // DimensionGroup|null              Sets the dimension group. See Dimension group.
    echo "Group (string): {$customer->getGroupToString()}<br />";                                                              					    // string|null
    echo "InUse (bool): {$customer->getInUse()}<br />";                                                                                   			// bool|null                        Indicates whether the balancesheet is used in a financial transaction or not. Read-only attribute.
    echo "InUse (string): {$customer->getInUseToString()}<br />";                                                                                   // string|null

    if ($customer->hasMessages()) {                                                                                              					// bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($customer->getMessages(), true) . "<br />";                                                  					// Array|null                       (Error) messages.
    }

    echo "Name: {$customer->getName()}<br />";                                                                                   					// string|null                      Name of the dimension.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($customer->getOffice(), true) . "</pre><br />";                      					// Office|null                      Office code.
    echo "Office (string): {$customer->getOfficeToString()}<br />";                                                              					// string|null
    echo "PaymentCondition:<br />";                                                                                                                 //                                  Sets the payment condition of a dimension.
    echo "PaymentCondition DiscountDays: {$customer->getPaymentConditionDiscountDays()}<br />";                                                     // int|null                         Number of discount days.
    echo "PaymentCondition DiscountPercentage: {$customer->getPaymentConditionDiscountPercentage()}<br />";                                         // float|null                       Discount percentage.
    echo "RemittanceAdvice:<br />";                                                                                                                 //
    echo "RemittanceAdvice SendMail: {$customer->getRemittanceAdviceSendMail()}<br />";                                                             // string|null                      Mandatory if sendtype = ByEmail, remittance advice will be sent using this e-mail address.
    echo "RemittanceAdvice SendType: {$customer->getRemittanceAdviceSendType()}<br />";                                                             // RemittanceAdviceSendType|null    To file manager, By e-mail
    echo "Result: {$customer->getResult()}<br />";                                                                               					// int|null                         Result (0 = error, 1 or empty = success).
    echo "ShortName: {$customer->getShortName()}<br />";                                                                         					// string|null                      Short name of the dimension.
    echo "Status: {$customer->getStatus()}<br />";                                                                               					// Status|null                      Status of the customer.
    echo "Touched: {$customer->getTouched()}<br />";                                                                                                // int|null                         Count of the number of times the dimension settings are changed. Read-only attribute.
    echo "Type (\\PhpTwinfield\\DimensionType): <pre>" . print_r($customer->getType(), true) . "</pre><br />";                                      // DimensionType|null               Dimension type. See Dimension type. Dimension type of customers is DEB.
    echo "Type (string): {$customer->getTypeToString()}<br />";                                                                                     // string|null
    echo "UID: {$customer->getUID()}<br />";                                                                                                        // string|null                      Unique identification of the dimension. Read-only attribute.
    echo "Website: {$customer->getWebsite()}<br />";                                                                                                // string|null                      Website of the dimension.

    $customerAddresses = $customer->getAddresses();                                                                                                 // array|null                       Array of CustomerAddress objects.

    foreach ($customerAddresses as $key => $customerAddress) {
        echo "CustomerAddress {$key}<br />";

        echo "City: {$customerAddress->getCity()}<br />";                                                                                           // string|null                      City.
        echo "Country (\\PhpTwinfield\\Country): <pre>" . print_r($customerAddress->getCountry(), true) . "</pre><br />";                      		// Country|null                     Country code. The ISO country codes are used.
        echo "Country (string): {$customerAddress->getCountryToString()}<br />";                                                              		// string|null
        echo "Default (bool): {$customerAddress->getDefault()}<br />";                                                                              // bool|null                        Is this the default address, only one default address is possible.
        echo "Default (string): {$customerAddress->getDefaultToString()}<br />";                                                                    // string|null
        echo "Email: {$customerAddress->getEmail()}<br />";                                                                                         // string|null
        echo "Field1: {$customerAddress->getField1()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field2: {$customerAddress->getField2()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field3: {$customerAddress->getField3()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field4: {$customerAddress->getField4()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type. Currently, field4 is reserved for VAT numbers. So only valid VAT numbers may be filled in.
        echo "Field5: {$customerAddress->getField5()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "Field6: {$customerAddress->getField6()}<br />";                                                                                       // string|null                      User defined fields, the labels are configured in the Dimension type.
        echo "ID: {$customerAddress->getID()}<br />";                                                                                               // int|null                         Sequence number of the address line.

        if ($customerAddress->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($customerAddress->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
        }

        echo "Name: {$customerAddress->getName()}<br />";                                                                    			            // string|null                     	Company name.
        echo "Postcode: {$customerAddress->getPostcode()}<br />";                                                                                   // string|null                      Postcode.
        echo "Result: {$customerAddress->getResult()}<br />";                                                                                       // int|null                         Result (0 = error, 1 or empty = success).
        echo "Telefax: {$customerAddress->getTelefax()}<br />";                                                                    			        // string|null                     	Fax number.
        echo "Telephone: {$customerAddress->getTelephone()}<br />";                                                                    			    // string|null                     	Telephone number.
        echo "Type: {$customerAddress->getType()}<br />";                                                                                           // AddressType|null                 The type of the address.
    }

    $customerBanks = $customer->getBanks();

    foreach ($customerBanks as $key => $customerBank) {
        echo "CustomerBank {$key}<br />";

        echo "AccountNumber: {$customerBank->getAccountNumber()}<br />";                                                                            // string|null                      Account number.
        echo "AddressField2: {$customerBank->getAddressField2()}<br />";                                                                            // string|null                      Bank address.
        echo "AddressField3: {$customerBank->getAddressField3()}<br />";                                                                            // string|null                      Bank address number.
        echo "Ascription: {$customerBank->getAscription()}<br />";                                                                                  // string|null                      Account holder.
        echo "BankName: {$customerBank->getBankName()}<br />";                                                                                      // string|null                      Bank name.
        echo "BicCode: {$customerBank->getBicCode()}<br />";                                                                                        // string|null                      BIC code.

        echo "Blocked (bool): {$customerBank->getBlocked()}<br />";                                                                                 // bool|null
        echo "Blocked (string): {$customerBank->getBlockedToString()}<br />";                                                                       // string|null
        echo "City: {$customerBank->getCity()}<br />";                                                                                              // string|null                      City.
        echo "Country (\\PhpTwinfield\\Country): <pre>" . print_r($customerBank->getCountry(), true) . "</pre><br />";                      		// Country|null                     Bank country code. The ISO country codes are used.
        echo "Country (string): {$customerBank->getCountryToString()}<br />";                                                              		    // string|null
        echo "Default (bool): {$customerBank->getDefault()}<br />";                                                                                 // bool|null                        Is this the default bank account, only one default bank account is possible.
        echo "Default (string): {$customerBank->getDefaultToString()}<br />";                                                                       // string|null
        echo "ID: {$customerBank->getID()}<br />";                                                                                                  // int|null                         Sequence number of the bank account line. When adding a new bank, do not supply the @id. When changing a bank account, supply the corresponding @id.
        echo "IBAN: {$customerBank->getIban()}<br />";                                                                                              // string|null                      IBAN account number.

        if ($customerBank->hasMessages()) {                                                                                					        // bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($customerBank->getMessages(), true) . "<br />";                                    					        // Array|null                       (Error) messages.
        }

        echo "NatBicCode: {$customerBank->getNatBicCode()}<br />";                                                                    			    // string|null              	    National bank code.
        echo "Postcode: {$customerBank->getPostcode()}<br />";                                                                                      // string|null                      Postcode.
        echo "Result: {$customerBank->getResult()}<br />";                                                                                          // int|null                         Result (0 = error, 1 or empty = success).
        echo "State: {$customerBank->getState()}<br />";                                                                    			            // string|null                     	State.
    }

    $customerCreditManagement = $customer->getCreditManagement();                                                                           		// CustomerCreditManagement|null    CustomerCreditManagement object.

    echo "CustomerCreditManagement<br />";
    echo "BaseCreditLimit (\\Money\\Money): <pre>" . print_r($customerCreditManagement->getBaseCreditLimit(), true) . "</pre><br />";               // Money|null                       The credit limit amount.
    echo "BaseCreditLimit (float): {$customerCreditManagement->getBaseCreditLimitToFloat()}<br />";                                                 // float|null
    echo "Blocked (bool): {$customerCreditManagement->getBlocked()}<br />";                                                                         // bool|null                        Indicates if related projects for this customer are blocked in time & expenses.
    echo "Blocked (string): {$customerCreditManagement->getBlockedToString()}<br />";                                                               // string|null
    echo "Blocked Locked (bool): {$customerCreditManagement->getBlockedLocked()}<br />";                                                            // bool|null
    echo "Blocked Locked (string): {$customerCreditManagement->getBlockedLockedToString()}<br />";                                                  // string|null
    echo "Blocked Modified (\\DateTimeInterface): <pre>" . print_r($customerCreditManagement->getBlockedModified(), true) . "</pre><br />";         // \DateTimeInterface|null
    echo "Blocked Modified (string): {$customerCreditManagement->getBlockedModifiedToString()}<br />";                                              // string|null
    echo "Comment: {$customerCreditManagement->getComment()}<br />";                                                                                // string|null                      Comment.
    echo "Freetext1 (bool): {$customerCreditManagement->getFreeText1()}<br />";                                                                     // bool|null                        Right of use.
    echo "Freetext1 (string): {$customerCreditManagement->getFreeText1ToString()}<br />";                                                           // string|null
    echo "Freetext2: {$customerCreditManagement->getFreetext2()}<br />";                                                                            // string|null                      Segment code.
    echo "Freetext3: {$customerCreditManagement->getFreetext3()}<br />";                                                                            // string|null                      Not in use.

    if ($customerCreditManagement->hasMessages()) {                                                                                					// bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($customerCreditManagement->getMessages(), true) . "<br />";                                    					// Array|null                       (Error) messages.
    }

    echo "ReminderEmail: {$customerCreditManagement->getReminderEmail()}<br />";                                                                    // string|null                      Mandatory if sendreminder is email.
    echo "ResponsibleUser (\\PhpTwinfield\\User): <pre>" . print_r($customerCreditManagement->getResponsibleUser(), true) . "</pre><br />";         // User|null                        The credit manager.
    echo "ResponsibleUser (string): {$customerCreditManagement->getResponsibleUserToString()}<br />";                                               // string|null
    echo "Result: {$customerCreditManagement->getResult()}<br />";                                                                                  // int|null                         Result (0 = error, 1 or empty = success).
    echo "SendReminder: {$customerCreditManagement->getSendReminder()}<br />";                                                                      // SendReminder|null                Determines if and how a customer will be reminded.

    echo "CustomerFinancials<br />";
    $customerFinancials = $customer->getFinancials();                                                                           			        // CustomerFinancials|null          CustomerFinancials object.

    echo "AccountType: {$customerFinancials->getAccountType()}<br />";                                                                              // AccountType|null                 Fixed value inherit.
    echo "CollectionSchema: {$customerFinancials->getCollectionSchema()}<br />";                                                                    // CollectionSchema|null            Collection schema information. Apply this information only when the customer invoices are collected by SEPA direct debit.
    echo "DueDays: {$customerFinancials->getDueDays()}<br />";                                                                                      // int|null                         The number of due days.
    echo "EBillMail: {$customerFinancials->getEBillMail()}<br />";                                                                                  // string|null                      The mail address the electronic sales invoice is sent to.
    echo "EBilling (bool): {$customerFinancials->getEBilling()}<br />";                                                                             // bool|null                        Determines if the sales invoices will be sent electronically to the customer.
    echo "EBilling (string): {$customerFinancials->getEBillingToString()}<br />";                                                                   // string|null
    echo "Level: {$customerFinancials->getLevel()}<br />";                                                                                          // int|null                        	Specifies the dimension level. Normally the level of customers is level 2. Read-only attribute.
    echo "MatchType: {$customerFinancials->getMatchType()}<br />";                                                                                  // MatchType|null                   Fixed value customersupplier.
    echo "MeansOfPayment: {$customerFinancials->getMeansOfPayment()}<br />";                                                                        // MeansOfPayment|null              The option none is only allowed in case payavailable is set to false. The option paymentfile is only allowed in case payavailable is set to true.

    if ($customerFinancials->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($customerFinancials->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
    }

    echo "PayAvailable (bool): {$customerFinancials->getPayAvailable()}<br />";                                                                     // bool|null                        Determines if direct debit is possible.
    echo "PayAvailable (string): {$customerFinancials->getPayAvailableToString()}<br />";                                                           // string|null
    echo "PayCode (\\PhpTwinfield\\PayCode): <pre>" . print_r($customerFinancials->getPayCode(), true) . "</pre><br />";                            // PayCode|null                     The code of the payment type in case direct debit is possible.
    echo "PayCode (string): {$customerFinancials->getPayCodeToString()}<br />";                                                                     // string|null
    echo "PayCodeID: {$customerFinancials->getPayCodeID()}<br />";                                                                                  // string|null
    echo "Result: {$customerFinancials->getResult()}<br />";                                                                                        // int|null                         Result (0 = error, 1 or empty = success).
    echo "SubAnalyse: {$customerFinancials->getSubAnalyse()}<br />";                                                                                // SubAnalyse|null                  Fixed value false.
    echo "SubstituteWith (\\PhpTwinfield\\Dummy): <pre>" . print_r($customerFinancials->getSubstituteWith(), true) . "</pre><br />";                // Dummy|null                       Default customer balancesheet account.
    echo "SubstituteWith (string): {$customerFinancials->getSubstituteWithToString()}<br />";                                                       // string|null
    echo "SubstituteWithID: {$customerFinancials->getSubstituteWithID()}<br />";                                                                    // string|null
    echo "SubstitutionLevel: {$customerFinancials->getSubstitutionLevel()}<br />";                                                                  // int|null                         Level of the balancesheet account. Fixed value 1.
    echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($customerFinancials->getVatCode(), true) . "</pre><br />";                            // VatCode|null                     Default VAT code.
    echo "VatCode (string): {$customerFinancials->getVatCodeToString()}<br />";                                                                     // string|null
    echo "VatCode Fixed (bool): {$customerFinancials->getVatCodeFixed()}<br />";                                                                    // bool|null
    echo "VatCode Fixed (string): {$customerFinancials->getVatCodeFixedToString()}<br />";                                                          // string|null

    $customerChildValidations = $customerFinancials->getChildValidations();                                                                         // array|null                       Array of CustomerChildValidations objects.

    foreach ($customerChildValidations as $key => $customerChildValidation) {
        echo "CustomerChildValidation {$key}<br />";

        echo "ElementValue: {$customerChildValidation->getElementValue()}<br />";                                                                   // string|null
        echo "Level: {$customerChildValidation->getLevel()}<br />";                                                                                 // int|null

        if ($customerChildValidation->hasMessages()) {                                                                                				// bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($customerChildValidation->getMessages(), true) . "<br />";                                    				// Array|null                       (Error) messages.
        }

        echo "Result: {$customerChildValidation->getResult()}<br />";                                                                               // int|null                         Result (0 = error, 1 or empty = success).
        echo "Type: {$customerChildValidation->getType()}<br />";                                                                                   // ChildValidationType|null
    }

    $customerCollectMandate = $customerFinancials->getCollectMandate();                                                                           	// CollectMandate|null              CollectMandate object.

    echo "CustomerCollectMandate<br />";
    echo "FirstRunDate (\\DateTimeInterface): <pre>" . print_r($customerCollectMandate->getFirstRunDate(), true) . "</pre><br />";                  // \DateTimeInterface|null          Date on which the first run was collected.
    echo "FirstRunDate (string): {$customerCollectMandate->getFirstRunDateToString()}<br />";                                                       // string|null
    echo "ID: {$customerCollectMandate->getID()}<br />";                                                                                            // string|null                      Mandate id which the debtor can collect with.

    if ($customerCollectMandate->hasMessages()) {                                                                                					// bool                             Object contains (error) messages true/false.
        echo "Messages: " . print_r($customerCollectMandate->getMessages(), true) . "<br />";                                    					// Array|null                       (Error) messages.
    }

    echo "SignatureDate (\\DateTimeInterface): <pre>" . print_r($customerCollectMandate->getSignatureDate(), true) . "</pre><br />";                // \DateTimeInterface|null          Date on which the mandate is signed.
    echo "SignatureDate (string): {$customerCollectMandate->getSignatureDateToString()}<br />";                                                     // string|null

    $customerPostingRules = $customer->getPostingRules();                                                                                           // array|null                       Array of CustomerPostingRule objects.

    foreach ($customerPostingRules as $key => $customerPostingRule) {
        echo "CustomerPostingRule {$key}<br />";

        echo "Amount (\\Money\\Money): <pre>" . print_r($customerPostingRule->getAmount(), true) . "</pre><br />";                                  // Money|null                       Amount.
        echo "Amount (float): {$customerPostingRule->getAmountToFloat()}<br />";                                                                    // float|null
        echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($customerPostingRule->getCurrency(), true) . "</pre><br />";                    // Currency|null                    Currency.
        echo "Currency (string): {$customerPostingRule->getCurrencyToString()}<br />";                                                              // string|null
        echo "Description: {$customerPostingRule->getDescription()}<br />";                                                                         // string|null                      Description.
        echo "ID: {$customerPostingRule->getID()}<br />";                                                                                           // int|null                         Sequence number of the posting rule. Fixed value 1.

        if ($customerPostingRule->hasMessages()) {                                                                                					// bool                             Object contains (error) messages true/false.
            echo "Messages: " . print_r($customerPostingRule->getMessages(), true) . "<br />";                                    					// Array|null                       (Error) messages.
        }

        echo "Result: {$customerPostingRule->getResult()}<br />";                                                                                   // int|null                         Result (0 = error, 1 or empty = success).
        echo "Status: {$customerPostingRule->getStatus()}<br />";                                                                               	// Status|null                      Status of the posting rule.

        $customerLines = $customerPostingRule->getLines();                                                                                          // array|null                       Array of CustomerLine objects.

        foreach ($customerLines as $key => $customerLine) {
            echo "CustomerLine {$key}<br />";

            echo "Description: {$customerLine->getDescription()}<br />";                                                                            // string|null                      Description.
            echo "Dimension1 (\\PhpTwinfield\\GeneralLedger): <pre>" . print_r($customerLine->getDimension1(), true) . "</pre><br />";              // GeneralLedger|null               General ledger.
            echo "Dimension1 (string): {$customerLine->getDimension1ToString()}<br />";                                                             // string|null
            echo "Dimension1ID: {$customerLine->getDimension1ID()}<br />";                                                                          // string|null
            echo "Dimension2 (\\PhpTwinfield\\CostCenter): <pre>" . print_r($customerLine->getDimension2(), true) . "</pre><br />";                 // CostCenter|null                  Cost center.
            echo "Dimension2 (string): {$customerLine->getDimension2ToString()}<br />";                                                             // string|null
            echo "Dimension2ID: {$customerLine->getDimension2ID()}<br />";                                                                          // string|null
            echo "Dimension3 (\\PhpTwinfield\\Dummy): <pre>" . print_r($customerLine->getDimension3(), true) . "</pre><br />";                      // Dummy|null                       Project or asset.
            echo "Dimension3 (string): {$customerLine->getDimension3ToString()}<br />";                                                             // string|null
            echo "Dimension3ID: {$customerLine->getDimension3ID()}<br />";                                                                          // string|null

            if ($customerLine->hasMessages()) {                                                                                					    // bool                             Object contains (error) messages true/false.
                echo "Messages: " . print_r($customerLine->getMessages(), true) . "<br />";                                    					    // Array|null                       (Error) messages.
            }

            echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($customerLine->getOffice(), true) . "</pre><br />";                      		// Office|null                      Destination company.
            echo "Office (string): {$customerLine->getOfficeToString()}<br />";                                                              		// string|null
            echo "Ratio: {$customerLine->getRatio()}<br />";                                                                                        // float|null                       The ratio of the posting rule line.
            echo "Result: {$customerLine->getResult()}<br />";                                                                                      // int|null                         Result (0 = error, 1 or empty = success).
            echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($customerLine->getVatCode(), true) . "</pre><br />";                          // VatCode|null                     VAT code.
            echo "VatCode (string): {$customerLine->getVatCodeToString()}<br />";                                                                   // string|null
        }
    }
}

// Copy an existing Customer to a new entity
if ($executeCopy) {
    try {
        $customer = $customerApiConnector->get("1000", $office);
    } catch (ResponseException $e) {
        $customer = $e->getReturnedObject();
    }

    $customer->setCode(null);                                                                                                                       // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$customer->setCode('1100');                                                                                                                   // string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.

    //Twinfield does not accept BankID's on new entities
    $customerBanks = $customer->getBanks();

    foreach ($customerBanks as $customerBank) {
        $customerBank->setID(null);
    }

    try {
        $customerCopy = $customerApiConnector->send($customer);
    } catch (ResponseException $e) {
        $customerCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customerCopy);
    echo "</pre>";

    echo "Result of copy process: {$customerCopy->getResult()}<br />";
    echo "Code of copied Customer: {$customerCopy->getCode()}<br />";
}

// Create a new Customer from scratch, alternatively read an existing Customer as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $customer = new \PhpTwinfield\Customer;

    // Required values for creating a new Customer
    $customer->setCode(null);                                                                                                                       // string|null                      Set to null to let Twinfield assign a Dimension code based on the Dimension type mask
    //$customer->setCode('1100');                                                                                                                   // string|null                      Dimension code, must be compliant with the mask of the DEB Dimension type.
    $customer->setName("Example Customer");                                                                                                         // string|null                      Name of the dimension.
    $customer->setOffice($office);                                                                                                                  // Office|null                      Office code.
    $customer->setOfficeFromString($officeCode);                                                                                                    // string|null

    // Optional values for creating a new Customer
    $customer->setBeginPeriod(0);                                                                                                                   // int|null                         Determines together with beginyear the period from which the dimension may be used.
    $customer->setBeginYear(0);                                                                                                                     // int|null                         Determines together with beginperiod the period from which the dimension may be used.
    $customer->setEndPeriod(0);                                                                                                                     // int|null                         Determines together with endyear the period till which the dimension may be used.
    $customer->setEndYear(0);                                                                                                                       // int|null                         Determines together with endperiod the period till which the dimension may be used.
    $customer->setShortName("ExmplCust");                                                                                                           // string|null                      Short name of the dimension.
    //$customer->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                                   // Status|null                      For creating and updating status may be left empty. For deleting deleted should be used.
    //$customer->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                                  // Status|null                      In case a dimension that is used in a transaction is deleted, its status has been changed into hide. Hidden dimensions can be activated by using active.
    //$customer->setStatusFromString('active');                                                                                                     // string|null
    //$customer->setStatusFromString('deleted');                                                                                                    // string|null
    $customer->setWebsite("www.example.com");                                                                                                       // string|null                      Website of the dimension.

    $article = new \PhpTwinfield\Article;
    $article->setCode('9060');
    $customer->setDiscountArticle($article);                                                                                                        // Article|null                     The discount or premium article.
    $customer->setDiscountArticleFromString("9060");                                                                                                // string|null

    $dimensionGroup = new \PhpTwinfield\DimensionGroup;
    $dimensionGroup->setCode('DIMGROUP');
    //$customer->setGroup($dimensionGroup);                                                                                                         // DimensionGroup|null              Sets the dimension group. See Dimension group.
    //$customer->setGroupFromString("DIMGROUP");                                                                                                    // string|null

    $customer->setPaymentConditionDiscountDays(3);                                                                                                  // int|null                         Number of discount days.
    $customer->setPaymentConditionDiscountPercentage(25);                                                                                           // int|null                         Discount percentage.

    $customer->setRemittanceAdviceSendMail("test@example.com");                                                                                     // string|null                      Mandatory if sendtype = ByEmail, remittance advice will be sent using this e-mail address.
    $customer->setRemittanceAdviceSendType(\PhpTwinfield\Enums\RemittanceAdviceSendType::BYEMAIL());                                                // RemittanceAdviceSendMail|null    To file manager, By e-mail
    $customer->setRemittanceAdviceSendTypeFromString('ByEmail');                                                                                    // string|null

    $customerCreditManagement = new \PhpTwinfield\CustomerCreditManagement;
    $customerCreditManagement->setBaseCreditLimit(\Money\Money::EUR(1000000));                                                                      // Money|null                       The credit limit amount.
    $customerCreditManagement->setBaseCreditLimitFromFloat(10000);                                                                                  // float|null
    $customerCreditManagement->setBlocked(false);                                                                                                   // bool|null                        Indicates if related projects for this customer are blocked in time & expenses.
    $customerCreditManagement->setBlockedFromString('false');                                                                                       // string|null
    $customerCreditManagement->setBlockedLocked(false);                                                                                             // bool|null
    $customerCreditManagement->setBlockedLockedFromString('false');                                                                                 // string|null
    $customerCreditManagement->setComment('Comment');                                                                                               // string|null                      Comment.
    $customerCreditManagement->setFreeText1(true);                                                                                                  // bool|null                        Right of use.
    $customerCreditManagement->setFreeText1FromString('true');                                                                                      // string|null
    $customerCreditManagement->setFreetext2(2);                                                                                                     // string|null                      Segment code.
    $customerCreditManagement->setFreetext3('');                                                                                                    // string|null
    $customerCreditManagement->setReminderEmail('test@example.com');                                                                                // string|null                      Mandatory if sendreminder is email.
    $user = new \PhpTwinfield\User;
    $user->setCode('TWINAPPS');
    $customerCreditManagement->setResponsibleUser($user);                                                                                           // User|null                        The credit manager.
    $customerCreditManagement->setResponsibleUserFromString('TWINAPPS');                                                                            // string|null
    $customerCreditManagement->setSendReminder(\PhpTwinfield\Enums\SendReminder::EMAIL());                                                          // SendReminder|null                Determines if and how a customer will be reminded.
    $customerCreditManagement->setSendReminderFromString('email');                                                                                  // string|null

    $customer->setCreditManagement($customerCreditManagement);                                                                                      // CustomerCreditManagement         Set the CustomerCreditManagement object tot the Customer object

    $customerFinancials = new \PhpTwinfield\CustomerFinancials;
    $customerFinancials->setCollectionSchema(\PhpTwinfield\Enums\CollectionSchema::CORE());
    $customerFinancials->setCollectionSchemaFromString('core');
    $customerFinancials->setDueDays(14);                                                                                                            // int|null                         The number of due days.
    $customerFinancials->setEBillMail('test@example.com');                                                                                          // string|null                      The mail address the electronic sales invoice is sent to.
    $customerFinancials->setEBilling(true);                                                                                                         // bool|null                        Determines if the sales invoices will be sent electronically to the customer.
    $customerFinancials->setEBillingFromString('true');                                                                                             // string|null
    $customerFinancials->setMeansOfPayment(\PhpTwinfield\Enums\MeansOfPayment::PAYMENTFILE());                                                      // MeansOfPayment|null              The option none is only allowed in case payavailable is set to false. The option paymentfile is only allowed in case payavailable is set to true.
    $customerFinancials->setMeansOfPaymentFromString('paymentfile');                                                                                // string|null
    $customerFinancials->setPayAvailable(true);                                                                                                     // bool|null                        Determines if direct debit is possible.
    $customerFinancials->setPayAvailableFromString('true');                                                                                         // string|null
    $payCode = new \PhpTwinfield\PayCode;
    $payCode->setCode('SEPANLCT');
    $customerFinancials->setPayCode($payCode);                                                                                                      // PayCode|null                     The code of the payment type in case direct debit is possible.
    $customerFinancials->setPayCodeFromString('SEPANLCT');                                                                                          // string|null
    $substituteWith = new \PhpTwinfield\GeneralLedger;
    $substituteWith->getCode('1535');
    $customerFinancials->setSubstituteWith($substituteWith);                                                                                        // GeneralLedger|null               Default customer balancesheet account.
    $customerFinancials->setSubstituteWithFromString('1535');                                                                                       // string|null
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $customerFinancials->setVatCode($vatCode);                                                                                                      // VatCode|null                     Default VAT code.
    $customerFinancials->setVatCodeFromString('VH');                                                                                                // string|null

    $customerCollectMandate = new \PhpTwinfield\CustomerCollectMandate;
    $firstRunDate = \DateTime::createFromFormat('d-m-Y', '01-07-2019');
    $customerCollectMandate->setFirstRunDate($firstRunDate);                                                                                        // DateTimeInterface|null           Date on which the first run was collected.
    $customerCollectMandate->setFirstRunDateFromString('20190701');                                                                                 // string|null
    $customerCollectMandate->setID('1010');                                                                                                         // string|null                      Mandate id which the debtor can collect with.
    $signatureDate = \DateTime::createFromFormat('d-m-Y', '01-07-2019');
    $customerCollectMandate->setSignatureDate($signatureDate);                                                                                      // DateTimeInterface|null           Date on which the first run was collected.
    $customerCollectMandate->setSignatureDateFromString('20190701');                                                                                // string|null

    $customerFinancials->setCollectMandate($customerCollectMandate);                                                                                // CustomerCollectMandate           Set the CustomerCollectMandate object tot the CustomerFinancials object

    $customer->setFinancials($customerFinancials);                                                                                                  // CustomerFinancials               Set the CustomerFinancials object tot the Customer object

    $customerAddress = new \PhpTwinfield\CustomerAddress;
    $customerAddress->setCity('Amsterdam');                                                                                                         // string|null                      City.
    $country = new \PhpTwinfield\Country;
    $country->setCode('NL');
    $customerAddress->setCountry($country);                                                                                                         // Country|null                     Country code. The ISO country codes are used.
    $customerAddress->setCountryFromString('NL');                                                                                                   // string|null
    $customerAddress->setDefault(true);                                                                                                             // bool|null                       	Is this the default address, only one default address is possible.
    $customerAddress->setDefaultFromString('true');                                                                                                 // string|null
    $customerAddress->setEmail('test@example.com');                                                                                                 // string|null
    $customerAddress->setField1('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $customerAddress->setField2('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $customerAddress->setField3('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $customerAddress->setField4(null);                                                                                                              // string|null                      User defined fields, the labels are configured in the Dimension type. Currently, field4 is reserved for VAT numbers. So only valid VAT numbers may be filled in.
    $customerAddress->setField5('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $customerAddress->setField6('');                                                                                                                // string|null                      User defined fields, the labels are configured in the Dimension type.
    $customerAddress->setID(1);                                                                                                                     // string|null                      Sequence number of the address line.
    $customerAddress->setName('Example Customer');                                                                                                  // string|null                      Company name.
    $customerAddress->setPostcode('9876YZ');                                                                                                        // string|null                      Postcode.
    $customerAddress->setTelefax('012-3456789');                                                                                                    // string|null                      Fax number.
    $customerAddress->setTelephone('987-654321');                                                                                                   // string|null                      Telephone number.
    $customerAddress->setType(\PhpTwinfield\Enums\AddressType::INVOICE());                                                                          // AddressType|null                 The type of the address.
    $customerAddress->setTypeFromString('invoice');                                                                                                 // string|null

    $customer->addAddress($customerAddress);                                                                                                        // CustomerAddress                  Add a CustomerAddress object to the Customer object
    //$customer->removeAddress(0);                                                                                                                  // int                              Remove an address based on the index of the address within the array

    $customerBank = new \PhpTwinfield\CustomerBank;
    $customerBank->setAccountNumber('123456789');                                                                                                   // string|null                      Account number.
    $customerBank->setAddressField2('Example Street');                                                                                              // string|null                      Bank address.
    $customerBank->setAddressField3('12');                                                                                                          // string|null                      Bank address number.
    $customerBank->setAscription('Example Customer');                                                                                               // string|null                      Account holder.
    $customerBank->setBankName('Example Bank');                                                                                                     // string|null                      Bank name.
    $customerBank->setBicCode('ABNANL2A');                                                                                                          // string|null                      BIC code.
    $customerBank->setBlocked(false);                                                                                                               // bool|null
    $customerBank->setBlockedFromString('false');                                                                                                   // string|null
    $customerBank->setCity('Amsterdam');                                                                                                            // string|null                      City.
    $country = new \PhpTwinfield\Country;
    $country->setCode('NL');
    $customerBank->setCountry($country);                                                                                                            // Country|null                     Bank country code. The ISO country codes are used.
    $customerBank->setCountryFromString('NL');                                                                                                      // string|null
    $customerBank->setDefault(true);                                                                                                                // bool|null                        Is this the default bank account, only one default bank account is possible.
    $customerBank->setDefaultFromString('true');                                                                                                    // string|null
    $customerBank->setID(null);                                                                                                                     // int|null                         Sequence number of the bank account line. When adding a new bank, do not supply the @id. When changing a bank account, supply the corresponding @id.
    $customerBank->setIban(null);                                                                                                                   // string|null                      IBAN account number.
    $customerBank->setNatBicCode('NL');                                                                                                             // string|null                      National bank code.
    $customerBank->setPostcode('1234AB');                                                                                                           // string|null                      Postcode.
    $customerBank->setState('Noord-Holland');                                                                                                       // string|null                      State.

    $customer->addBank($customerBank);                                                                                                              // CustomerBank                     Add a CustomerBank object to the Customer object
    //$customer->removeBank(0);                                                                                                                     // int                              Remove a bank based on the index of the bank within the array

    $customerPostingRule = new \PhpTwinfield\CustomerPostingRule;
    $customerPostingRule->setAmount(\Money\Money::EUR(10000));                                                                                      // Money|null                       Amount.
    $customerPostingRule->setAmountFromFloat(100);                                                                                                  // float|null
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $customerPostingRule->setCurrency($currency);                                                                                                   // Currency|null                    Currency.
    $customerPostingRule->setCurrencyFromString('EUR');                                                                                             // string|null
    $customerPostingRule->setDescription('Example PostingRule');                                                                                    // string|null                      Description.
    $customerPostingRule->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());                                                                          // Status|null                      For creating and updating active should be used. For deleting deleted should be used.
    //$customerPostingRule->setStatus(\PhpTwinfield\Enums\Status::DELETED());                                                                       // Status|null
    $customerPostingRule->setStatusFromString('active');                                                                                            // string|null
    //$customerPostingRule->setStatusFromString('deleted');                                                                                         // string|null

    $customerLine = new \PhpTwinfield\CustomerLine;
    $customerLine->setDescription('Example Line');                                                                                                  // string|null                      Description.
    $dimension1 = new \PhpTwinfield\GeneralLedger;
    $dimension1->setCode('1535');
    $customerLine->setDimension1($dimension1);                                                                                                      // GeneralLedger|null               General ledger.
    $customerLine->setDimension1FromString('1535');                                                                                                 // string|null
    $costCenter = new \PhpTwinfield\CostCenter;
    $costCenter->setCode('00000');
    $customerLine->setDimension2($costCenter);                                                                                                      // CostCenter|null                  Cost center.
    $customerLine->setDimension2FromString ('00000');                                                                                               // string|null
    $activity = new \PhpTwinfield\Activity;
    $activity->setCode('P0000');
    $customerLine->setDimension3($activity);                                                                                                        // Dummy|null                       Project or asset.
    $customerLine->setDimension3FromString('P0000');                                                                                                // string|null
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('NLA0000001');
    //$customerLine->setOffice($destOffice);                                                                                                        // Office|null                      Destination company.
    //$customerLine->setOfficeFromString('NLA0000001');                                                                                             // string|null
    $customerLine->setRatio(1);                                                                                                                     // float|null                       The ratio of the posting rule line.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VH');
    $customerLine->setVatCode($vatCode);                                                                                                            // VatCode|null                     Default VAT code.
    $customerLine->setVatCodeFromString('VH');                                                                                                      // string|null

    $customerPostingRule->addLine($customerLine);                                                                                                   // CustomerLine                     Add a CustomerLine object to the CustomerPostingRule object
    //$customerPostingRule->removeLine(0);                                                                                                          // int                              Remove a line based on the index of the line within the array

    $customer->addPostingRule($customerPostingRule);                                                                                                // CustomerPostingRule              Add a CustomerPostingRule object to the Customer object
    //$customer->removePostingRule(0);                                                                                                              // int                              Remove a posting rule based on the index of the posting rule within the array

    try {
        $customerNew = $customerApiConnector->send($customer);
    } catch (ResponseException $e) {
        $customerNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customerNew);
    echo "</pre>";

    echo "Result of creation process: {$customerNew->getResult()}<br />";
    echo "Code of new Customer: {$customerNew->getCode()}<br />";
}

// Delete a Customer based off the passed in code and optionally the office.
if ($executeDelete) {
    try {
        $customerDeleted = $customerApiConnector->delete("1003", $office);
    } catch (ResponseException $e) {
        $customerDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($customerDeleted);
    echo "</pre>";

    echo "Result of deletion process: {$customerDeleted->getResult()}<br />";
}