<?php
/* JournalTransaction
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Transactions
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions
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

/* Transaction API Connector
 * \PhpTwinfield\ApiConnectors\TransactionApiConnector
 * Available methods: delete, get, send, sendAll
 */

/* Note there is no list all functionality in the TransactionApiConnector.
 * Use the BrowseDataApiConnector to get information about transactions and then read a transaction using the TransactionApiConnector
 */

// Run all or only some of the following examples
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$transactionApiConnector = new \PhpTwinfield\ApiConnectors\TransactionApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* JournalTransaction
 * \PhpTwinfield\JournalTransaction
 * Available getters: getAutoBalanceVat, getBookingReference, getCode, getCurrency, getDate, getDateRaiseWarning, getDateRaiseWarningToString, getDestiny, getFreeText1, getFreeText2, getFreeText3, getInputDate, getLineClassName, getMessages, getModificationDate, getNumber, getOffice, getOrigin, getPeriod, getRaiseWarning, getRegime, getResult, hasMessages, getLines
 * Available setters: setAutoBalanceVat, setCode, setCurrency, setDate, setDateRaiseWarning, setDestiny, setFreeText1, setFreeText2, setFreeText3, setInputDate, setLines, setModificationDate, setNumber, setOffice, setOrigin, setPeriod, setRaiseWarning, setRegime, addLine
 */

/* JournalTransactionLine
 * \PhpTwinfield\JournalTransactionLine
 * Available getters: getBaseline, getBaseValue, getBaseValueOpen, getComment, getCurrencyDate, getDebitCredit, getDescription, getDestOffice, getDim1, getDim2, getDim3, getFreeChar, getID, getInvoiceNumber, getLineType, getMatchLevel, getMatchStatus, getMessages, getPerformanceCountry, getPerformanceDate, getPerformanceType, getPerformanceVatNumber, getRate, getReference, getRelation, getRepRate, getRepValue, getRepValueOpen, getResult, getSignedValue, getTransaction, getValue, getVatBaseTurnover, getVatBaseValue, getVatCode, getVatRepTurnover, getVatRepValue, getVatTurnover, getVatValue, hasMessages
 * Available setters: setBaseline, setBaseValue, setBaseValueOpen, setComment, setCurrencyDate, setDebitCredit, setDescription, setDestOffice, setDim1, setDim2, setDim3, setFreeChar, setID, setInvoiceNumber, setLineType, setMatchLevel, setMatchStatus, setPerformanceCountry, setPerformanceDate, setPerformanceType, setPerformanceVatNumber, setRate, setRelation, setRepRate, setRepValue, setRepValueOpen, setTransaction, setValue, setVatBaseTurnover, setVatBaseValue, setVatCode, setVatRepTurnover, setVatRepValue, setVatTurnover, setVatValue
 */

/* Read a JournalTransaction based off the passed in journal day book code, transaction number and optionally the office.
 * The used transaction type, in the example below MEMO, depends on the administration. It is possible that there are multiple journal day book codes in an administration.
 * See https://accounting.twinfield.com/UI/#/Settings/Company/TransactionTypes for available codes for (journal) day books in your office
 */

if ($executeRead) {
    try {
        $journalTransaction = $transactionApiConnector->get(\PhpTwinfield\JournalTransaction::class, "MEMO", 201900003, $office);
    } catch (ResponseException $e) {
        $journalTransaction = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($journalTransaction);
    echo "</pre>";

    echo "JournalTransaction<br />";
    echo "AutoBalanceVat (bool): {$journalTransaction->getAutoBalanceVat()}<br />";                                                                            // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    echo "AutoBalanceVat (string): " . Util::formatBoolean($journalTransaction->getAutoBalanceVat()) . "<br />";                                               // string|null
    echo "BookingReference (\\PhpTwinfield\\BookingReference): <pre>" . print_r($journalTransaction->getBookingReference(), true) . "</pre><br />";            // BookingReference|null        The Booking reference
    echo "Code: {$journalTransaction->getCode()}<br />";                                                                                                       // string|null                  Transaction type code.
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($journalTransaction->getCurrency(), true) . "</pre><br />";                                    // Currency|null                Currency code.
    echo "Currency (string): " . Util::objectToStr($journalTransaction->getCurrency()) . "<br />";                                                             // string|null
    echo "Date (\\DateTimeInterface): <pre>" . print_r($journalTransaction->getDate(), true) . "</pre><br />";                                                 // DateTimeInterface|null       Transaction date.
    echo "Date (string): " . Util::formatDate($journalTransaction->getDate()) . "<br />";                                                                      // string|null
    echo "DateRaiseWarning (bool): {$journalTransaction->getDateRaiseWarning()}<br />";                                                                        // bool|null                    Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    echo "DateRaiseWarning (string): {$journalTransaction->getDateRaiseWarningToString()}<br />";                                                              // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    echo "Destiny: {$journalTransaction->getDestiny()}<br />";                                                                                                 // Destiny|null                 Attribute to indicate the destiny of the journal transaction. Only used in the request XML. temporary = journal transaction will be saved as provisional. final = journal transaction will be saved as final.
    echo "FreeText1: {$journalTransaction->getFreeText1()}<br />";                                                                                             // string|null                  Free text field 1 as entered on the transaction type.
    echo "FreeText2: {$journalTransaction->getFreeText2()}<br />";                                                                                             // string|null                  Free text field 2 as entered on the transaction type.
    echo "FreeText3: {$journalTransaction->getFreeText3()}<br />";                                                                                             // string|null                  Free text field 3 as entered on the transaction type.
    echo "InputDate (\\DateTimeInterface): <pre>" . print_r($journalTransaction->getInputDate(), true) . "</pre><br />";                                       // DateTimeInterface|null       The date/time on which the transaction was created. Read-only attribute.
    echo "InputDate (string): " . Util::formatDate($journalTransaction->getInputDate()) . "<br />";                                                            // string|null

    if ($journalTransaction->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($journalTransaction->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "ModificationDate (\\DateTimeInterface): <pre>" . print_r($journalTransaction->getModificationDate(), true) . "</pre><br />";                         // DateTimeInterface|null       The date/time on which the journal transaction was modified the last time. Read-only attribute.
    echo "ModificationDate (string): " . Util::formatDate($journalTransaction->getModificationDate()) . "<br />";                                              // string|null
    echo "Number: {$journalTransaction->getNumber()}<br />";                                                                                                   // int|null                     Transaction number. When creating a new journal transaction, don't include this tag as the transaction number is determined by the system. When updating a journal transaction, the related transaction number should be provided.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($journalTransaction->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($journalTransaction->getOffice()) . "<br />";                                                                 // string|null
    echo "Origin: {$journalTransaction->getOrigin()}<br />";                                                                                                   // string|null                  The journal transaction origin. Read-only attribute.
    echo "Period: {$journalTransaction->getPeriod()}<br />";                                                                                                   // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    echo "RaiseWarning (bool): {$journalTransaction->getRaiseWarning()}<br />";                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    echo "RaiseWarning (string): " . Util::formatBoolean($journalTransaction->getRaiseWarning()) . "<br />";                                                   // string|null
    echo "Regime: {$journalTransaction->getRegime()}<br />";                                                                                                   // Regime|null                  Type of regime. You can post transactions as 'Fiscal', 'Commercial', 'Economic' or 'Generic'. The default regime is 'Generic'. 'Generic' means that the transaction is visible for all regimes.
    echo "Result: {$journalTransaction->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).

    $journalTransactionLines = $journalTransaction->getLines();                                                                                                // array|null                   Array of JournalTransactionLine objects.

    foreach ($journalTransactionLines as $key => $journalTransactionLine) {
        echo "JournalTransactionLine {$key}<br />";

        echo "Baseline: {$journalTransactionLine->getBaseline()}<br />";                                                                                       // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
        echo "BaseValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getBaseValue(), true) . "</pre><br />";                                    // Money|null                   Amount in the base currency.
        echo "BaseValue (string): " . Util::formatMoney($journalTransactionLine->getBaseValue()) . "<br />";                                                   // string|null
        echo "BaseValueOpen (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getBaseValueOpen(), true) . "</pre><br />";                            // Money|null                   Only if line type is detail. The amount still owed in base currency. Read-only attribute.
        echo "BaseValueOpen (string): " . Util::formatMoney($journalTransactionLine->getBaseValueOpen()) . "<br />";                                           // string|null
        echo "Comment: {$journalTransactionLine->getComment()}<br />";                                                                                         // string|null                  Comment set on the transaction line.
        echo "CurrencyDate (\\DateTimeInterface): <pre>" . print_r($journalTransactionLine->getCurrencyDate(), true) . "</pre><br />";                         // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the journal book is set to Allowed or Mandatory.
        echo "CurrencyDate (string): " . Util::formatDate($journalTransactionLine->getCurrencyDate()) . "<br />";                                              // string|null
        echo "DebitCredit: {$journalTransactionLine->getDebitCredit()}<br />";                                                                                 // DebitCredit|null             The debit/credit indicator of the journal transaction line.
        echo "Description: {$journalTransactionLine->getDescription()}<br />";                                                                                 // string|null                  Description of the transaction line.
        echo "DestOffice (\\PhpTwinfield\\Office): <pre>" . print_r($journalTransactionLine->getDestOffice(), true) . "</pre><br />";                          // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
        echo "DestOffice (string): " . Util::objectToStr($journalTransactionLine->getDestOffice()) . "<br />";                                                 // string|null
        echo "Dim1: <pre>" . print_r($journalTransactionLine->getDim1(), true) . "</pre><br />";                                                               // object|null                  If line type = detail the journal balance account or profit and loss account.
        echo "Dim1 (string): " . Util::objectToStr($journalTransactionLine->getDim1()) . "<br />";                                                             // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
        echo "Dim2: <pre>" . print_r($journalTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = detail the customer or supplier or the cost center or empty.
        echo "Dim2 (string): " . Util::objectToStr($journalTransactionLine->getDim2()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "Dim3: <pre>" . print_r($journalTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = detail the project or asset or empty.
        echo "Dim3 (string): " . Util::objectToStr($journalTransactionLine->getDim3()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "FreeChar: {$journalTransactionLine->getFreeChar()}<br />";                                                                                       // string|null                  Free character field.
        echo "ID: {$journalTransactionLine->getID()}<br />";                                                                                                   // int|null                     Line ID.
        echo "InvoiceNumber: {$journalTransactionLine->getInvoiceNumber()}<br />";                                                                             // string|null                  Invoice number. Only if line type is detail.
        echo "LineType: {$journalTransactionLine->getLineType()}<br />";                                                                                       // LineType|null                Line type.
        echo "MatchLevel: {$journalTransactionLine->getMatchLevel()}<br />";                                                                                   // int|null                     Only if line type is detail. The level of the matchable dimension. Read-only attribute.
        echo "MatchStatus: {$journalTransactionLine->getMatchStatus()}<br />";                                                                                 // MatchStatus|null             Payment status of the journal transaction. If line type vat always notmatchable. Read-only attribute.

        if ($journalTransactionLine->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($journalTransactionLine->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
        }

        echo "PerformanceCountry (\\PhpTwinfield\\Country): <pre>" . print_r($journalTransactionLine->getPerformanceCountry(), true) . "</pre><br />";         // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
        echo "PerformanceCountry (string): " . Util::objectToStr($journalTransactionLine->getPerformanceCountry()) . "<br />";                                 // string|null
        echo "PerformanceDate (\\DateTimeInterface): <pre>" . print_r($journalTransactionLine->getPerformanceDate(), true) . "</pre><br />";                   // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
        echo "PerformanceDate (string): " . Util::formatDate($journalTransactionLine->getPerformanceDate()) . "<br />";                                        // string|null
        echo "PerformanceType: {$journalTransactionLine->getPerformanceType()}<br />";                                                                         // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
        echo "PerformanceVatNumber: {$journalTransactionLine->getPerformanceVatNumber()}<br />";                                                               // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
        echo "Rate: {$journalTransactionLine->getRate()}<br />";                                                                                               // float|null                   The exchange rate used for the calculation of the base amount.
        echo "Reference (\\PhpTwinfield\\MatchReference): <pre>" . print_r($journalTransactionLine->getReference(), true) . "</pre><br />";                    // MatchReference|null          Match reference
        echo "Relation: {$journalTransactionLine->getRelation()}<br />";                                                                                       // int|null                     Only if line type is detail. Read-only attribute.
        echo "RepRate: {$journalTransactionLine->getRepRate()}<br />";                                                                                         // float|null                   The exchange rate used for the calculation of the reporting amount.
        echo "RepValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getRepValue(), true) . "</pre><br />";                                      // Money|null                   Amount in the reporting currency.
        echo "RepValue (string): " . Util::formatMoney($journalTransactionLine->getRepValue()) . "<br />";                                                     // string|null
        echo "RepValueOpen (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getRepValueOpen(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. The amount still owed in reporting currency. Read-only attribute.
        echo "RepValueOpen (string): " . Util::formatMoney($journalTransactionLine->getRepValueOpen()) . "<br />";                                             // string|null
        echo "Result: {$journalTransactionLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "SignedValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getSignedValue(), true) . "</pre><br />";                                // Money|null
        echo "SignedValue (string): " . Util::formatMoney($journalTransactionLine->getSignedValue()) . "<br />";                                               // string|null
        echo "Value (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getValue(), true) . "</pre><br />";                                            // Money|null                   If line type = detail amount without VAT. If line type = vat VAT amount.
        echo "Value (string): " . Util::formatMoney($journalTransactionLine->getValue()) . "<br />";                                                           // string|null
        echo "VatBaseTurnover (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatBaseTurnover(), true) . "</pre><br />";                        // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
        echo "VatBaseTurnover (string): " . Util::formatMoney($journalTransactionLine->getVatBaseTurnover()) . "<br />";                                       // string|null
        echo "VatBaseValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatBaseValue(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. VAT amount in base currency.
        echo "VatBaseValue (string): " . Util::formatMoney($journalTransactionLine->getVatBaseValue()) . "<br />";                                             // string|null
        echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($journalTransactionLine->getVatCode(), true) . "</pre><br />";                               // VatCode|null                 Only if line type is detail or vat. VAT code.
        echo "VatCode (string): " . Util::objectToStr($journalTransactionLine->getVatCode()) . "<br />";                                                       // string|null
        echo "VatRepTurnover (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatRepTurnover(), true) . "</pre><br />";                          // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
        echo "VatRepTurnover (string): " . Util::formatMoney($journalTransactionLine->getVatRepTurnover()) . "<br />";                                         // string|null
        echo "VatRepValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatRepValue(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepValue (string): " . Util::formatMoney($journalTransactionLine->getVatRepValue()) . "<br />";                                               // string|null
        echo "VatTurnover (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatTurnover(), true) . "</pre><br />";                                // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the journal transaction.
        echo "VatTurnover (string): " . Util::formatMoney($journalTransactionLine->getVatTurnover()) . "<br />";                                               // string|null
        echo "VatValue (\\Money\\Money): <pre>" . print_r($journalTransactionLine->getVatValue(), true) . "</pre><br />";                                      // Money|null                   Only if line type is detail. VAT amount in the currency of the journal transaction.
        echo "VatValue (string): " . Util::formatMoney($journalTransactionLine->getVatValue()) . "<br />";                                                     // string|null
    }
}

// Copy an existing JournalTransaction to a new entity
if ($executeCopy) {
    try {
        $journalTransaction = $transactionApiConnector->get(\PhpTwinfield\JournalTransaction::class, "MEMO", 201900003, $office);
    } catch (ResponseException $e) {
        $journalTransaction = $e->getReturnedObject();
    }

    $journalTransaction->setNumber(null);

    //Optional, but recommended so your copy is not posted to final immediately
    $journalTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());

    try {
        $journalTransactionCopy = $transactionApiConnector->send($journalTransaction);
    } catch (ResponseException $e) {
        $journalTransactionCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($journalTransactionCopy);
    echo "</pre>";

    echo "Result of copy process: {$journalTransactionCopy->getResult()}<br />";
    echo "Number of copied JournalTransaction: {$journalTransactionCopy->getNumber()}<br />";
}

// Create a new JournalTransaction from scratch, alternatively read an existing JournalTransaction as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $journalTransaction = new \PhpTwinfield\JournalTransaction;

    // Required values for creating a new JournalTransaction
    $journalTransaction->setCode('MEMO');                                                                                                                       // string|null                  Transaction type code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $journalTransaction->setCurrency($currency);                                                                                                               // Currency|null                Currency code.
    //$journalTransaction->setCurrency(\PhpTwinfield\Currency::fromCode("EUR"));                                                                               // string|null
    $journalTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());                                                                                 // Destiny|null                 Attribute to indicate the destiny of the journal transaction. Only used in the request XML. temporary = journal transaction will be saved as provisional.
    //$journalTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::FINAL());                                                                                   // Destiny|null                 final = journal transaction will be saved as final.
    $journalTransaction->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $journalTransaction->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null

    // Optional values for creating a new JournalTransaction
    $journalTransaction->setAutoBalanceVat(true);                                                                                                              // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    $date = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    $journalTransaction->setDate($date);                                                                                                                       // DateTimeInterface|null       Transaction date. Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    $journalTransaction->setDate(Util::parseDate("20190901"));                                                                                                 // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    $journalTransaction->setDateRaiseWarning(false);                                                                                                           // bool|null
    //$journalTransaction->setFreeText1('Example FreeText 1');                                                                                                 // string|null                  Free text field 1 as entered on the transaction type.
    //$journalTransaction->setFreeText2('Example FreeText 2');                                                                                                 // string|null                  Free text field 2 as entered on the transaction type.
    //$journalTransaction->setFreeText3('Example FreeText 3');                                                                                                 // string|null                  Free text field 3 as entered on the transaction type.
    //$journalTransaction->setNumber(201900011);                                                                                                               // int|null                     Transaction number. When creating a new journal transaction, don't include this tag as the transaction number is determined by the system. When updating a journal transaction, the related transaction number should be provided.
    //$journalTransaction->setPeriod("2019/07");                                                                                                               // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    $journalTransaction->setRaiseWarning(true);                                                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    //$journalTransaction->setRegime(\PhpTwinfield\Enums\Regime::GENERIC());                                                                                   // Regime|null                  Type of regime. You can post transactions as 'Fiscal', 'Commercial', 'Economic' or 'Generic'. The default regime is 'Generic'. 'Generic' means that the transaction is visible for all regimes.

    // The minimum amount of JournalTransactionLines linked to an JournalTransaction object is 2
    $journalTransactionLine1 = new \PhpTwinfield\JournalTransactionLine;
    $journalTransactionLine1->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $journalTransactionLine1->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $journalTransactionLine1->setDescription("Example Description on line with ID 1");                                                                         // string|null          	       Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $journalTransactionLine1->setDim1($dim1);                                                                                                                  // object|null                  If line type = detail the journal balance account or profit and loss account.
    $journalTransactionLine1->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $journalTransactionLine1->setID(1);                                                                                                                        // int|null            	       Line ID.
    $journalTransactionLine1->setValue(\Money\Money::EUR(10000));                                                                                              // Money|null                   If line type = detail amount without VAT. If line type = vat VAT amount.

    $journalTransaction->addLine($journalTransactionLine1);                                                                                                    // JournalTransactionLine       Add a JournalTransactionLine object to the JournalTransaction object

    $journalTransactionLine2 = new \PhpTwinfield\JournalTransactionLine;
    $journalTransactionLine2->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $journalTransactionLine2->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $journalTransactionLine2->setDescription("Example Description on line with ID 2");                                                                         // string|null          	       Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $journalTransactionLine2->setDim1($dim1);                                                                                                                  // object|null                  If line type = detail the journal balance account or profit and loss account.
    $journalTransactionLine2->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $journalTransactionLine2->setID(2);                                                                                                                        // int|null            	       Line ID.
    $journalTransactionLine2->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = detail amount without VAT. If line type = vat VAT amount.

    //$journalTransactionLine2->setInvoiceNumber(201900001);                                                                                                   // string|null                  Invoice number. Only if line type is detail.
    //$journalTransactionLine2->setComment('Example Comments');                                                                                                // string|null                  Comment set on the transaction line.
    $currencyDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$journalTransaction->setCurrencyDate($currencyDate);                                                                                                     // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the journal book is set to Allowed or Mandatory.
    //$journalTransaction->setCurrencyDate(Util::parseDate("20190901"));                                                                                       // string|null
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('1234');
    //$journalTransaction->setDestOffice($destOffice);                                                                                                         // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
    //$journalTransaction->setDestOffice(\PhpTwinfield\Office::fromCode('1234'));                                                                              // string|null
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1001');
    //$journalTransactionLine2->setDim2($dim2);                                                                                                                // object|null                  If line type = detail the customer or supplier or the cost center or empty. If line type = vat empty.
    //$journalTransactionLine2->setDim2(\PhpTwinfield\Customer::fromCode('1001'));                                                                             // string|null
    $dim3 = new \PhpTwinfield\Project;
    $dim3->setCode('P0000');
    //$journalTransactionLine2->setDim3($dim3);                                                                                                                // object|null                  If line type = detail the project or asset or empty. If line type = vat empty.
    //$journalTransactionLine2->setDim3(\PhpTwinfield\Project::fromCode('P0000'));                                                                             // string|null
    //$journalTransactionLine2->setFreeChar('A');                                                                                                              // string|null                  Free character field.
    $performanceCountry = new \PhpTwinfield\Country;
    $performanceCountry->setCode('NL');
    //$journalTransactionLine2->setPerformanceCountry($performanceCountry);                                                                                    // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
    //$journalTransactionLine2->setPerformanceCountry(\PhpTwinfield\Country::fromCode('NL'));                                                                  // string|null
    $performanceDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$journalTransactionLine2->setPerformanceDate($performanceDate);                                                                                          // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
    //$journalTransactionLine2->setPerformanceDate(Util::parseDate("20190901"));                                                                               // string|null
    //$journalTransactionLine2->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                           // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
    //$journalTransactionLine2->setPerformanceVatNumber('NL1234567890B01');                                                                                    // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
    //$journalTransactionLine2->setRepValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Amount in the reporting currency.
    //$journalTransactionLine2->setRate(1);                                                                                                                    // float|null                   The exchange rate used for the calculation of the base amount.
    //$journalTransactionLine2->setRepRate(1);                                                                                                                 // float|null                   The exchange rate used for the calculation of the reporting amount.
    //$journalTransactionLine2->setVatBaseValue(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is detail. VAT amount in base currency.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VN');
    //$journalTransactionLine2->setVatCode($vatCode);                                                                                                          // VatCode|null                 Only if line type is detail or vat. VAT code.
    //$journalTransactionLine2->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));                                                                             // string|null
    //$journalTransactionLine2->setVatRepValue(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is detail. VAT amount in reporting currency.
    //$journalTransactionLine2->setVatValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is detail. VAT amount in the currency of the journal transaction.

    $journalTransaction->addLine($journalTransactionLine2);

    //$journalTransactionLine3 = new \PhpTwinfield\JournalTransactionLine;
    //$journalTransactionLine3->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Line type.
    //$journalTransactionLine3->setBaseline(1);                                                                                                                // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
    //$journalTransactionLine3->setVatBaseTurnover(\Money\Money::EUR(-10000));                                                                                 // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
    //$journalTransactionLine3->setVatRepTurnover(\Money\Money::EUR(-10000));                                                                                  // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
    //$journalTransactionLine3->setVatTurnover(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the journal transaction.

    try {
        $journalTransactionNew = $transactionApiConnector->send($journalTransaction);
    } catch (ResponseException $e) {
        $journalTransactionNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($journalTransactionNew);
    echo "</pre>";

    echo "Result of creation process: {$journalTransactionNew->getResult()}<br />";
    echo "Number of new JournalTransaction: {$journalTransactionNew->getNumber()}<br />";
}

// Delete a BrankTransaction based off the passed in office, code, number and given reason
if ($executeDelete) {
    $bookingReference = new \PhpTwinfield\BookingReference($office, 'MEMO', 201900005);

    try {
        $journalTransactionDeleted = $transactionApiConnector->delete($bookingReference, 'Example reason');
    } catch (ResponseException $e) {
        $journalTransactionDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($journalTransactionDeleted);
    echo "</pre>";
}