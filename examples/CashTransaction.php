<?php
/* CashTransaction
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Transactions
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions
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

/* CashTransaction
 * \PhpTwinfield\CashTransaction
 * Available getters: getAutoBalanceVat, getBookingReference, getCloseValue, getCode, getCurrency, getDate, getDateRaiseWarning, getDateRaiseWarningToString, getDestiny, getFreeText1, getFreeText2, getFreeText3, getInputDate, getLineClassName, getMessages, getModificationDate, getNumber, getOffice, getOrigin, getPeriod, getRaiseWarning, getResult, getStartValue, getStatementNumber, hasMessages, getLines
 * Available setters: setAutoBalanceVat, setCode, setCurrency, setDate, setDateRaiseWarning, setDestiny, setFreeText1, setFreeText2, setFreeText3, setInputDate, setLines, setModificationDate, setNumber, setOffice, setOrigin, setPeriod, setRaiseWarning, setStartValue, setStatementNumber, addLine
 */

/* CashTransactionLine
 * \PhpTwinfield\CashTransactionLine
 * Available getters: getBaseValue, getBaseValueOpen, getComment, getCurrencyDate, getDebitCredit, getDescription, getDestOffice, getDim1, getDim2, getDim3, getFreeChar, getID, getLineType, getMatchLevel, getMatchStatus, getMessages, getPerformanceCountry, getPerformanceDate, getPerformanceType, getPerformanceVatNumber, getRate, getReference, getRelation, getRepRate, getRepValue, getRepValueOpen, getResult, getSignedValue, getTransaction, getValue, getVatBaseTotal, getVatBaseTurnover, getVatBaseValue, getVatCode, getVatRepTotal, getVatRepTurnover, getVatRepValue, getVatTotal, getVatTurnover, getVatValue, hasMessages
 * Available setters: setBaseValue, setBaseValueOpen, setComment, setCurrencyDate, setDebitCredit, setDescription, setDestOffice, setDim1, setDim2, setDim3, setFreeChar, setID, setLineType, setMatchLevel, setMatchStatus, setPerformanceCountry, setPerformanceDate, setPerformanceType, setPerformanceVatNumber, setRate, setRelation, setRepRate, setRepValue, setRepValueOpen, setTransaction, setValue, setVatBaseTotal, setVatBaseTurnover, setVatBaseValue, setVatCode, setVatRepTotal, setVatRepTurnover, setVatRepValue, setVatTotal, setVatTurnover, setVatValue
 */

/* Read a CashTransaction based off the passed in cash day book code, transaction number and optionally the office.
 * The used transaction type, in the example below KAS, depends on the administration. It is possible that there are multiple cash day book codes in an administration.
 * See https://accounting.twinfield.com/UI/#/Settings/Company/TransactionTypes for available codes for (cash) day books in your office
 */

if ($executeRead) {
    try {
        $cashTransaction = $transactionApiConnector->get(\PhpTwinfield\CashTransaction::class, "KAS", 201900011, $office);
    } catch (ResponseException $e) {
        $cashTransaction = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($cashTransaction);
    echo "</pre>";

    echo "CashTransaction<br />";
    echo "AutoBalanceVat (bool): {$cashTransaction->getAutoBalanceVat()}<br />";                                                                            // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    echo "AutoBalanceVat (string): " . Util::formatBoolean($cashTransaction->getAutoBalanceVat()) . "<br />";                                               // string|null
    echo "BookingReference (\\PhpTwinfield\\BookingReference): <pre>" . print_r($cashTransaction->getBookingReference(), true) . "</pre><br />";            // BookingReference|null        The Booking reference
    echo "CloseValue (\\Money\\Money): <pre>" . print_r($cashTransaction->getCloseValue(), true) . "</pre><br />";                                          // Money|null                   Closing balance. If not provided, the closing balance is set to zero.
    echo "CloseValue (string): " . Util::formatMoney($cashTransaction->getCloseValue()) . "<br />";                                                         // string|null
    echo "Code: {$cashTransaction->getCode()}<br />";                                                                                                       // string|null                  Transaction type code.
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($cashTransaction->getCurrency(), true) . "</pre><br />";                                    // Currency|null                Currency code.
    echo "Currency (string): " . Util::objectToStr($cashTransaction->getCurrency()) . "<br />";                                                             // string|null
    echo "Date (\\DateTimeInterface): <pre>" . print_r($cashTransaction->getDate(), true) . "</pre><br />";                                                 // DateTimeInterface|null       Transaction date.
    echo "Date (string): " . Util::formatDate($cashTransaction->getDate()) . "<br />";                                                                      // string|null
    echo "DateRaiseWarning (bool): {$cashTransaction->getDateRaiseWarning()}<br />";                                                                        // bool|null                    Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    echo "DateRaiseWarning (string): {$cashTransaction->getDateRaiseWarningToString()}<br />";                                                              // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    echo "Destiny: {$cashTransaction->getDestiny()}<br />";                                                                                                 // Destiny|null                 Attribute to indicate the destiny of the cash transaction. Only used in the request XML. temporary = cash transaction will be saved as provisional. final = cash transaction will be saved as final.
    echo "FreeText1: {$cashTransaction->getFreeText1()}<br />";                                                                                             // string|null                  Free text field 1 as entered on the transaction type.
    echo "FreeText2: {$cashTransaction->getFreeText2()}<br />";                                                                                             // string|null                  Free text field 2 as entered on the transaction type.
    echo "FreeText3: {$cashTransaction->getFreeText3()}<br />";                                                                                             // string|null                  Free text field 3 as entered on the transaction type.
    echo "InputDate (\\DateTimeInterface): <pre>" . print_r($cashTransaction->getInputDate(), true) . "</pre><br />";                                       // DateTimeInterface|null       The date/time on which the transaction was created. Read-only attribute.
    echo "InputDate (string): " . Util::formatDate($cashTransaction->getInputDate()) . "<br />";                                                            // string|null

    if ($cashTransaction->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($cashTransaction->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "ModificationDate (\\DateTimeInterface): <pre>" . print_r($cashTransaction->getModificationDate(), true) . "</pre><br />";                         // DateTimeInterface|null       The date/time on which the cash transaction was modified the last time. Read-only attribute.
    echo "ModificationDate (string): " . Util::formatDate($cashTransaction->getModificationDate()) . "<br />";                                              // string|null
    echo "Number: {$cashTransaction->getNumber()}<br />";                                                                                                   // int|null                     Transaction number. When creating a new cash transaction, don't include this tag as the transaction number is determined by the system. When updating a cash transaction, the related transaction number should be provided.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($cashTransaction->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($cashTransaction->getOffice()) . "<br />";                                                                 // string|null
    echo "Origin: {$cashTransaction->getOrigin()}<br />";                                                                                                   // string|null                  The cash transaction origin. Read-only attribute.
    echo "Period: {$cashTransaction->getPeriod()}<br />";                                                                                                   // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    echo "RaiseWarning (bool): {$cashTransaction->getRaiseWarning()}<br />";                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    echo "RaiseWarning (string): " . Util::formatBoolean($cashTransaction->getRaiseWarning()) . "<br />";                                                   // string|null
    echo "Result: {$cashTransaction->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).
    echo "StartValue (\\Money\\Money): <pre>" . print_r($cashTransaction->getStartValue(), true) . "</pre><br />";                                          // Money|null                   Opening balance. If not provided, the opening balance is set to zero.
    echo "StartValue (string): " . Util::formatMoney($cashTransaction->getStartValue()) . "<br />";                                                         // string|null
    echo "StatementNumber: {$cashTransaction->getStatementNumber()}<br />";                                                                                 // int|null                     Number of the cash statement. Don't confuse this number with the transaction number.

    $cashTransactionLines = $cashTransaction->getLines();                                                                                                   // array|null                   Array of CashTransactionLine objects.

    foreach ($cashTransactionLines as $key => $cashTransactionLine) {
        echo "CashTransactionLine {$key}<br />";

        echo "BaseValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getBaseValue(), true) . "</pre><br />";                                    // Money|null                   Amount in the base currency.
        echo "BaseValue (string): " . Util::formatMoney($cashTransactionLine->getBaseValue()) . "<br />";                                                   // string|null
        echo "BaseValueOpen (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getBaseValueOpen(), true) . "</pre><br />";                            // Money|null                   Only if line type is detail. The amount still owed in base currency. Read-only attribute.
        echo "BaseValueOpen (string): " . Util::formatMoney($cashTransactionLine->getBaseValueOpen()) . "<br />";                                           // string|null
        echo "Comment: {$cashTransactionLine->getComment()}<br />";                                                                                         // string|null                  Comment set on the transaction line.
        echo "CurrencyDate (\\DateTimeInterface): <pre>" . print_r($cashTransactionLine->getCurrencyDate(), true) . "</pre><br />";                         // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the cash book is set to Allowed or Mandatory.
        echo "CurrencyDate (string): " . Util::formatDate($cashTransactionLine->getCurrencyDate()) . "<br />";                                              // string|null
        echo "DebitCredit: {$cashTransactionLine->getDebitCredit()}<br />";                                                                                 // DebitCredit|null             If line type = total, based on the sum of the individual cash transaction lines. In case of a cash addition debit. In case of a cash withdrawal credit. If line type = detail, credit in case money is received and debit in case money is paid. If line type = vat, based on the sum of the vat amounts of the individual cash transaction lins. In case of a cash addition credit. In case of a cash withdrawal debit.
        echo "Description: {$cashTransactionLine->getDescription()}<br />";                                                                                 // string|null                  Description of the transaction line.
        echo "DestOffice (\\PhpTwinfield\\Office): <pre>" . print_r($cashTransactionLine->getDestOffice(), true) . "</pre><br />";                          // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
        echo "DestOffice (string): " . Util::objectToStr($cashTransactionLine->getDestOffice()) . "<br />";                                                 // string|null
        echo "Dim1: <pre>" . print_r($cashTransactionLine->getDim1(), true) . "</pre><br />";                                                               // object|null                  If line type = total the cash balance account. If line type = detail the customer or supplier balance account or profit and loss account.
        echo "Dim1 (string): " . Util::objectToStr($cashTransactionLine->getDim1()) . "<br />";                                                             // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
        echo "Dim2: <pre>" . print_r($cashTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the customer or supplier or the cost center or empty.
        echo "Dim2 (string): " . Util::objectToStr($cashTransactionLine->getDim2()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "Dim3: <pre>" . print_r($cashTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the project or asset or empty.
        echo "Dim3 (string): " . Util::objectToStr($cashTransactionLine->getDim3()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "FreeChar: {$cashTransactionLine->getFreeChar()}<br />";                                                                                       // string|null                  Free character field.
        echo "ID: {$cashTransactionLine->getID()}<br />";                                                                                                   // int|null                     Line ID.
        echo "LineType: {$cashTransactionLine->getLineType()}<br />";                                                                                       // LineType|null                Line type.
        echo "MatchLevel: {$cashTransactionLine->getMatchLevel()}<br />";                                                                                   // int|null                     Only if line type is detail. The level of the matchable dimension. Read-only attribute.
        echo "MatchStatus: {$cashTransactionLine->getMatchStatus()}<br />";                                                                                 // MatchStatus|null             Payment status of the cash transaction. If line type total or vat always notmatchable. Read-only attribute.

        if ($cashTransactionLine->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($cashTransactionLine->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
        }

        echo "PerformanceCountry (\\PhpTwinfield\\Country): <pre>" . print_r($cashTransactionLine->getPerformanceCountry(), true) . "</pre><br />";         // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
        echo "PerformanceCountry (string): " . Util::objectToStr($cashTransactionLine->getPerformanceCountry()) . "<br />";                                 // string|null
        echo "PerformanceDate (\\DateTimeInterface): <pre>" . print_r($cashTransactionLine->getPerformanceDate(), true) . "</pre><br />";                   // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
        echo "PerformanceDate (string): " . Util::formatDate($cashTransactionLine->getPerformanceDate()) . "<br />";                                        // string|null
        echo "PerformanceType: {$cashTransactionLine->getPerformanceType()}<br />";                                                                         // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
        echo "PerformanceVatNumber: {$cashTransactionLine->getPerformanceVatNumber()}<br />";                                                               // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
        echo "Rate: {$cashTransactionLine->getRate()}<br />";                                                                                               // float|null                   The exchange rate used for the calculation of the base amount.
        echo "Reference (\\PhpTwinfield\\MatchReference): <pre>" . print_r($cashTransactionLine->getReference(), true) . "</pre><br />";                    // MatchReference|null          Match reference
        echo "Relation: {$cashTransactionLine->getRelation()}<br />";                                                                                       // int|null                     Only if line type is detail. Read-only attribute.
        echo "RepRate: {$cashTransactionLine->getRepRate()}<br />";                                                                                         // float|null                   The exchange rate used for the calculation of the reporting amount.
        echo "RepValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getRepValue(), true) . "</pre><br />";                                      // Money|null                   Amount in the reporting currency.
        echo "RepValue (string): " . Util::formatMoney($cashTransactionLine->getRepValue()) . "<br />";                                                     // string|null
        echo "RepValueOpen (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getRepValueOpen(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. The amount still owed in reporting currency. Read-only attribute.
        echo "RepValueOpen (string): " . Util::formatMoney($cashTransactionLine->getRepValueOpen()) . "<br />";                                             // string|null
        echo "Result: {$cashTransactionLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "SignedValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getSignedValue(), true) . "</pre><br />";                                // Money|null
        echo "SignedValue (string): " . Util::formatMoney($cashTransactionLine->getSignedValue()) . "<br />";                                               // string|null
        echo "Value (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getValue(), true) . "</pre><br />";                                            // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
        echo "Value (string): " . Util::formatMoney($cashTransactionLine->getValue()) . "<br />";                                                           // string|null
        echo "VatBaseTotal (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatBaseTotal(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The total VAT amount in base currency.
        echo "VatBaseTotal (string): " . Util::formatMoney($cashTransactionLine->getVatBaseTotal()) . "<br />";                                             // string|null
        echo "VatBaseTurnover (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatBaseTurnover(), true) . "</pre><br />";                        // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
        echo "VatBaseTurnover (string): " . Util::formatMoney($cashTransactionLine->getVatBaseTurnover()) . "<br />";                                       // string|null
        echo "VatBaseValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatBaseValue(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. VAT amount in base currency.
        echo "VatBaseValue (string): " . Util::formatMoney($cashTransactionLine->getVatBaseValue()) . "<br />";                                             // string|null
        echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($cashTransactionLine->getVatCode(), true) . "</pre><br />";                               // VatCode|null                 Only if line type is detail or vat. VAT code.
        echo "VatCode (string): " . Util::objectToStr($cashTransactionLine->getVatCode()) . "<br />";                                                       // string|null
        echo "VatRepTotal (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatRepTotal(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepTotal (string): " . Util::formatMoney($cashTransactionLine->getVatRepTotal()) . "<br />";                                               // string|null
        echo "VatRepTurnover (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatRepTurnover(), true) . "</pre><br />";                          // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
        echo "VatRepTurnover (string): " . Util::formatMoney($cashTransactionLine->getVatRepTurnover()) . "<br />";                                         // string|null
        echo "VatRepValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatRepValue(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepValue (string): " . Util::formatMoney($cashTransactionLine->getVatRepValue()) . "<br />";                                               // string|null
        echo "VatTotal (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatTotal(), true) . "</pre><br />";                                      // Money|null                   Only if line type is total. The total VAT amount in the currency of the cash transaction.
        echo "VatTotal (string): " . Util::formatMoney($cashTransactionLine->getVatTotal()) . "<br />";                                                     // string|null
        echo "VatTurnover (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatTurnover(), true) . "</pre><br />";                                // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the cash transaction.
        echo "VatTurnover (string): " . Util::formatMoney($cashTransactionLine->getVatTurnover()) . "<br />";                                               // string|null
        echo "VatValue (\\Money\\Money): <pre>" . print_r($cashTransactionLine->getVatValue(), true) . "</pre><br />";                                      // Money|null                   Only if line type is detail. VAT amount in the currency of the cash transaction.
        echo "VatValue (string): " . Util::formatMoney($cashTransactionLine->getVatValue()) . "<br />";                                                     // string|null
    }
}

// Copy an existing CashTransaction to a new entity
if ($executeCopy) {
    try {
        $cashTransaction = $transactionApiConnector->get(\PhpTwinfield\CashTransaction::class, "KAS", 201900011, $office);
    } catch (ResponseException $e) {
        $cashTransaction = $e->getReturnedObject();
    }

    $cashTransaction->setNumber(null);

    //Optional, but recommended so your copy is not posted to final immediately
    $cashTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());

    try {
        $cashTransactionCopy = $transactionApiConnector->send($cashTransaction);
    } catch (ResponseException $e) {
        $cashTransactionCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($cashTransactionCopy);
    echo "</pre>";

    echo "Result of copy process: {$cashTransactionCopy->getResult()}<br />";
    echo "Number of copied CashTransaction: {$cashTransactionCopy->getNumber()}<br />";
}

// Create a new CashTransaction from scratch, alternatively read an existing CashTransaction as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $cashTransaction = new \PhpTwinfield\CashTransaction;

    // Required values for creating a new CashTransaction
    $cashTransaction->setCode('KAS');                                                                                                                       // string|null                  Transaction type code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $cashTransaction->setCurrency($currency);                                                                                                               // Currency|null                Currency code.
    //$cashTransaction->setCurrency(\PhpTwinfield\Currency::fromCode("EUR"));                                                                               // string|null
    $cashTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());                                                                                 // Destiny|null                 Attribute to indicate the destiny of the cash transaction. Only used in the request XML. temporary = cash transaction will be saved as provisional.
    //$cashTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::FINAL());                                                                                   // Destiny|null                 final = cash transaction will be saved as final.
    $cashTransaction->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $cashTransaction->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null

    // Optional values for creating a new CashTransaction
    $cashTransaction->setAutoBalanceVat(true);                                                                                                              // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    $date = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    $cashTransaction->setDate($date);                                                                                                                       // DateTimeInterface|null       Transaction date. Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    $cashTransaction->setDate(Util::parseDate("20190901"));                                                                                                 // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    $cashTransaction->setDateRaiseWarning(false);                                                                                                           // bool|null
    //$cashTransaction->setFreeText1('Example FreeText 1');                                                                                                 // string|null                  Free text field 1 as entered on the transaction type.
    //$cashTransaction->setFreeText2('Example FreeText 2');                                                                                                 // string|null                  Free text field 2 as entered on the transaction type.
    //$cashTransaction->setFreeText3('Example FreeText 3');                                                                                                 // string|null                  Free text field 3 as entered on the transaction type.
    //$cashTransaction->setNumber(201900011);                                                                                                               // int|null                     Transaction number. When creating a new cash transaction, don't include this tag as the transaction number is determined by the system. When updating a cash transaction, the related transaction number should be provided.
    //$cashTransaction->setPeriod("2019/07");                                                                                                               // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    $cashTransaction->setRaiseWarning(true);                                                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    //$cashTransactionLine->setStartValue(\Money\Money::EUR(1000));                                                                                         // Money|null                   Opening balance. If not provided, the opening balance is set to zero. (Equals 10.00 EUR)
    $cashTransaction->setStatementNumber(20);                                                                                                               // int|null                     Number of the cash statement. Don't confuse this number with the transaction number.

    // The minimum amount of CashTransactionLines linked to an CashTransaction object is 2 (1 total, 1 detail)
    $cashTransactionLine1 = new \PhpTwinfield\CashTransactionLine;
    $cashTransactionLine1->setLineType(\PhpTwinfield\Enums\LineType::TOTAL());                                                                              // LineType|null                Line type.

    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('1010');
    $cashTransactionLine1->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the cash balance account. If line type = detail the customer or supplier balance account or profit and loss account.
    $cashTransactionLine1->setDim1(\PhpTwinfield\GeneralLedger::fromCode('1010'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $cashTransactionLine1->setID(1);                                                                                                                        // int|null            	        Line ID.
    $cashTransactionLine1->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
    //$cashTransactionLine1->setVatBaseTotal(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is total. The total VAT amount in base currency.
    //$cashTransactionLine1->setVatRepTotal(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
    //$cashTransactionLine1->setVatTotal(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is total. The total VAT amount in the currency of the cash transaction.

    $cashTransaction->addLine($cashTransactionLine1);                                                                                                       // CashTransactionLine          Add a CashTransactionLine object to the CashTransaction object

    $cashTransactionLine2 = new \PhpTwinfield\CashTransactionLine;
    $cashTransactionLine2->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $cashTransactionLine2->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $cashTransactionLine2->setDescription("Example Description on line with ID 2");                                                                         // string|null          	    Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $cashTransactionLine2->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the cash balance account. If line type = detail the customer or supplier balance account or profit and loss account.
    $cashTransactionLine2->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $cashTransactionLine2->setID(2);                                                                                                                        // int|null            	        Line ID.
    $cashTransactionLine2->setValue(\Money\Money::EUR(-10000));                                                                                              // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.

    //$cashTransactionLine2->setComment('Example Comments');                                                                                                // string|null                  Comment set on the transaction line.
    $currencyDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$cashTransaction->setCurrencyDate($currencyDate);                                                                                                     // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the cash book is set to Allowed or Mandatory.
    //$cashTransaction->setCurrencyDate(Util::parseDate("20190901"));                                                                                       // string|null
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('1234');
    //$cashTransaction->setDestOffice($destOffice);                                                                                                         // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
    //$cashTransaction->setDestOffice(\PhpTwinfield\Office::fromCode('1234'));                                                                              // string|null
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1001');
    //$cashTransactionLine2->setDim2($dim2);                                                                                                                // object|null                  If line type = total empty. If line type = detail the customer or supplier or the cost center or empty. If line type = vat empty.
    //$cashTransactionLine2->setDim2(\PhpTwinfield\Customer::fromCode('1001'));                                                                             // string|null
    $dim3 = new \PhpTwinfield\Project;
    $dim3->setCode('P0000');
    //$cashTransactionLine2->setDim3($dim3);                                                                                                                // object|null                  If line type = total empty. If line type = detail the project or asset or empty. If line type = vat empty.
    //$cashTransactionLine2->setDim3(\PhpTwinfield\Project::fromCode('P0000'));                                                                             // string|null
    //$cashTransactionLine2->setFreeChar('A');                                                                                                              // string|null                  Free character field.
    $performanceCountry = new \PhpTwinfield\Country;
    $performanceCountry->setCode('NL');
    //$cashTransactionLine2->setPerformanceCountry($performanceCountry);                                                                                    // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
    //$cashTransactionLine2->setPerformanceCountry(\PhpTwinfield\Country::fromCode('NL'));                                                                  // string|null
    $performanceDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$cashTransactionLine2->setPerformanceDate($performanceDate);                                                                                          // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
    //$cashTransactionLine2->setPerformanceDate(Util::parseDate("20190901"));                                                                               // string|null
    //$cashTransactionLine2->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                           // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
    //$cashTransactionLine2->setPerformanceVatNumber('NL1234567890B01');                                                                                    // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
    //$cashTransactionLine2->setRepValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Amount in the reporting currency.
    //$cashTransactionLine2->setRate(1);                                                                                                                    // float|null                   The exchange rate used for the calculation of the base amount.
    //$cashTransactionLine2->setRepRate(1);                                                                                                                 // float|null                   The exchange rate used for the calculation of the reporting amount.
    //$cashTransactionLine2->setVatBaseValue(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is detail. VAT amount in base currency.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VN');
    //$cashTransactionLine2->setVatCode($vatCode);                                                                                                          // VatCode|null                 Only if line type is detail or vat. VAT code.
    //$cashTransactionLine2->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));                                                                             // string|null
    //$cashTransactionLine2->setVatRepValue(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is detail. VAT amount in reporting currency.
    //$cashTransactionLine2->setVatValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is detail. VAT amount in the currency of the cash transaction.

    $cashTransaction->addLine($cashTransactionLine2);

    //$cashTransactionLine3 = new \PhpTwinfield\CashTransactionLine;
    //$cashTransactionLine3->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Line type.
    //$cashTransactionLine3->setVatBaseTurnover(\Money\Money::EUR(-10000));                                                                                 // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
    //$cashTransactionLine3->setVatRepTurnover(\Money\Money::EUR(-10000));                                                                                  // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
    //$cashTransactionLine3->setVatTurnover(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the cash transaction.

    try {
        $cashTransactionNew = $transactionApiConnector->send($cashTransaction);
    } catch (ResponseException $e) {
        $cashTransactionNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($cashTransactionNew);
    echo "</pre>";

    echo "Result of creation process: {$cashTransactionNew->getResult()}<br />";
    echo "Number of new CashTransaction: {$cashTransactionNew->getNumber()}<br />";
}

// Delete a BrankTransaction based off the passed in office, code, number and given reason
if ($executeDelete) {
    $bookingReference = new \PhpTwinfield\BookingReference($office, 'KAS', 201900026);

    try {
        $cashTransactionDeleted = $transactionApiConnector->delete($bookingReference, 'Example reason');
    } catch (ResponseException $e) {
        $cashTransactionDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($cashTransactionDeleted);
    echo "</pre>";
}
