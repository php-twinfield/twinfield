<?php
/* BankTransaction
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Transactions
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions
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

/* BankTransaction
 * \PhpTwinfield\BankTransaction
 * Available getters: getAutoBalanceVat, getBookingReference, getCloseValue, getCode, getCurrency, getDate, getDateRaiseWarning, getDateRaiseWarningToString, getDestiny, getFreeText1, getFreeText2, getFreeText3, getInputDate, getLineClassName, getMessages, getModificationDate, getNumber, getOffice, getOrigin, getPeriod, getRaiseWarning, getResult, getStartValue, getStatementNumber, hasMessages, getLines
 * Available setters: setAutoBalanceVat, setCode, setCurrency, setDate, setDateRaiseWarning, setDestiny, setFreeText1, setFreeText2, setFreeText3, setInputDate, setLines, setModificationDate, setNumber, setOffice, setOrigin, setPeriod, setRaiseWarning, setStartValue, setStatementNumber, addLine
 */

/* BankTransactionLine
 * \PhpTwinfield\BankTransactionLine
 * Available getters: getBaseValue, getBaseValueOpen, getComment, getCurrencyDate, getDebitCredit, getDescription, getDestOffice, getDim1, getDim2, getDim3, getFreeChar, getID, getLineType, getMatchLevel, getMatchStatus, getMessages, getPerformanceCountry, getPerformanceDate, getPerformanceType, getPerformanceVatNumber, getRate, getReference, getRelation, getRepRate, getRepValue, getRepValueOpen, getResult, getSignedValue, getTransaction, getValue, getVatBaseTotal, getVatBaseTurnover, getVatBaseValue, getVatCode, getVatRepTotal, getVatRepTurnover, getVatRepValue, getVatTotal, getVatTurnover, getVatValue, hasMessages
 * Available setters: setBaseValue, setBaseValueOpen, setComment, setCurrencyDate, setDebitCredit, setDescription, setDestOffice, setDim1, setDim2, setDim3, setFreeChar, setID, setLineType, setMatchLevel, setMatchStatus, setPerformanceCountry, setPerformanceDate, setPerformanceType, setPerformanceVatNumber, setRate, setRelation, setRepRate, setRepValue, setRepValueOpen, setTransaction, setValue, setVatBaseTotal, setVatBaseTurnover, setVatBaseValue, setVatCode, setVatRepTotal, setVatRepTurnover, setVatRepValue, setVatTotal, setVatTurnover, setVatValue
 */

/* Read a BankTransaction based off the passed in bank day book code, transaction number and optionally the office.
 * The used transaction type, in the example below BNK depends on the administration. It is possible that there are multiple bank day book codes in an administration.
 * See https://accounting.twinfield.com/UI/#/Settings/Company/TransactionTypes for available codes for (bank) day books in your office
 */

if ($executeRead) {
    try {
        $bankTransaction = $transactionApiConnector->get(\PhpTwinfield\BankTransaction::class, "BNK", 201900011, $office);
    } catch (ResponseException $e) {
        $bankTransaction = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($bankTransaction);
    echo "</pre>";

    echo "BankTransaction<br />";
    echo "AutoBalanceVat (bool): {$bankTransaction->getAutoBalanceVat()}<br />";                                                                            // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    echo "AutoBalanceVat (string): " . Util::formatBoolean($bankTransaction->getAutoBalanceVat()) . "<br />";                                               // string|null
    echo "BookingReference (\\PhpTwinfield\\BookingReference): <pre>" . print_r($bankTransaction->getBookingReference(), true) . "</pre><br />";            // BookingReference|null        The Booking reference
    echo "CloseValue (\\Money\\Money): <pre>" . print_r($bankTransaction->getCloseValue(), true) . "</pre><br />";                                          // Money|null                   Closing balance. If not provided, the closing balance is set to zero.
    echo "CloseValue (string): " . Util::formatMoney($bankTransaction->getCloseValue()) . "<br />";                                                         // string|null
    echo "Code: {$bankTransaction->getCode()}<br />";                                                                                                       // string|null                  Transaction type code.
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($bankTransaction->getCurrency(), true) . "</pre><br />";                                    // Currency|null                Currency code.
    echo "Currency (string): " . Util::objectToStr($bankTransaction->getCurrency()) . "<br />";                                                             // string|null
    echo "Date (\\DateTimeInterface): <pre>" . print_r($bankTransaction->getDate(), true) . "</pre><br />";                                                 // DateTimeInterface|null       Transaction date.
    echo "Date (string): " . Util::formatDate($bankTransaction->getDate()) . "<br />";                                                                      // string|null
    echo "DateRaiseWarning (bool): {$bankTransaction->getDateRaiseWarning()}<br />";                                                                        // bool|null                    Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    echo "DateRaiseWarning (string): {$bankTransaction->getDateRaiseWarningToString()}<br />";                                                              // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    echo "Destiny: {$bankTransaction->getDestiny()}<br />";                                                                                                 // Destiny|null                 Attribute to indicate the destiny of the bank transaction. Only used in the request XML. temporary = bank transaction will be saved as provisional. final = bank transaction will be saved as final.
    echo "FreeText1: {$bankTransaction->getFreeText1()}<br />";                                                                                             // string|null                  Free text field 1 as entered on the transaction type.
    echo "FreeText2: {$bankTransaction->getFreeText2()}<br />";                                                                                             // string|null                  Free text field 2 as entered on the transaction type.
    echo "FreeText3: {$bankTransaction->getFreeText3()}<br />";                                                                                             // string|null                  Free text field 3 as entered on the transaction type.
    echo "InputDate (\\DateTimeInterface): <pre>" . print_r($bankTransaction->getInputDate(), true) . "</pre><br />";                                       // DateTimeInterface|null       The date/time on which the transaction was created. Read-only attribute.
    echo "InputDate (string): " . Util::formatDate($bankTransaction->getInputDate()) . "<br />";                                                            // string|null

    if ($bankTransaction->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($bankTransaction->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "ModificationDate (\\DateTimeInterface): <pre>" . print_r($bankTransaction->getModificationDate(), true) . "</pre><br />";                         // DateTimeInterface|null       The date/time on which the bank transaction was modified the last time. Read-only attribute.
    echo "ModificationDate (string): " . Util::formatDate($bankTransaction->getModificationDate()) . "<br />";                                              // string|null
    echo "Number: {$bankTransaction->getNumber()}<br />";                                                                                                   // int|null                     Transaction number. When creating a new bank transaction, don't include this tag as the transaction number is determined by the system. When updating a bank transaction, the related transaction number should be provided.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($bankTransaction->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($bankTransaction->getOffice()) . "<br />";                                                                 // string|null
    echo "Origin: {$bankTransaction->getOrigin()}<br />";                                                                                                   // string|null                  The bank transaction origin. Read-only attribute.
    echo "Period: {$bankTransaction->getPeriod()}<br />";                                                                                                   // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    echo "RaiseWarning (bool): {$bankTransaction->getRaiseWarning()}<br />";                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    echo "RaiseWarning (string): " . Util::formatBoolean($bankTransaction->getRaiseWarning()) . "<br />";                                                   // string|null
    echo "Result: {$bankTransaction->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).
    echo "StartValue (\\Money\\Money): <pre>" . print_r($bankTransaction->getStartValue(), true) . "</pre><br />";                                          // Money|null                   Opening balance. If not provided, the opening balance is set to zero.
    echo "StartValue (string): " . Util::formatMoney($bankTransaction->getStartValue()) . "<br />";                                                         // string|null
    echo "StatementNumber: {$bankTransaction->getStatementNumber()}<br />";                                                                                 // int|null                     Number of the bank statement. Don't confuse this number with the transaction number.

    $bankTransactionLines = $bankTransaction->getLines();                                                                                                   // array|null                   Array of BankTransactionLine objects.

    foreach ($bankTransactionLines as $key => $bankTransactionLine) {
        echo "BankTransactionLine {$key}<br />";

        echo "BaseValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getBaseValue(), true) . "</pre><br />";                                    // Money|null                   Amount in the base currency.
        echo "BaseValue (string): " . Util::formatMoney($bankTransactionLine->getBaseValue()) . "<br />";                                                   // string|null
        echo "BaseValueOpen (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getBaseValueOpen(), true) . "</pre><br />";                            // Money|null                   Only if line type is detail. The amount still owed in base currency. Read-only attribute.
        echo "BaseValueOpen (string): " . Util::formatMoney($bankTransactionLine->getBaseValueOpen()) . "<br />";                                           // string|null
        echo "Comment: {$bankTransactionLine->getComment()}<br />";                                                                                         // string|null                  Comment set on the transaction line.
        echo "CurrencyDate (\\DateTimeInterface): <pre>" . print_r($bankTransactionLine->getCurrencyDate(), true) . "</pre><br />";                         // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the bank book is set to Allowed or Mandatory.
        echo "CurrencyDate (string): " . Util::formatDate($bankTransactionLine->getCurrencyDate()) . "<br />";                                              // string|null
        echo "DebitCredit: {$bankTransactionLine->getDebitCredit()}<br />";                                                                                 // DebitCredit|null             If line type = total, based on the sum of the individual bank transaction lines. In case of a bank addition debit. In case of a bank withdrawal credit. If line type = detail, credit in case money is received and debit in case money is paid. If line type = vat, based on the sum of the vat amounts of the individual bank transaction lins. In case of a bank addition credit. In case of a bank withdrawal debit.
        echo "Description: {$bankTransactionLine->getDescription()}<br />";                                                                                 // string|null                  Description of the transaction line.
        echo "DestOffice (\\PhpTwinfield\\Office): <pre>" . print_r($bankTransactionLine->getDestOffice(), true) . "</pre><br />";                          // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
        echo "DestOffice (string): " . Util::objectToStr($bankTransactionLine->getDestOffice()) . "<br />";                                                 // string|null
        echo "Dim1: <pre>" . print_r($bankTransactionLine->getDim1(), true) . "</pre><br />";                                                               // object|null                  If line type = total the bank balance account. If line type = detail the customer or supplier balance account or profit and loss account.
        echo "Dim1 (string): " . Util::objectToStr($bankTransactionLine->getDim1()) . "<br />";                                                             // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
        echo "Dim2: <pre>" . print_r($bankTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the customer or supplier or the cost center or empty.
        echo "Dim2 (string): " . Util::objectToStr($bankTransactionLine->getDim2()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "Dim3: <pre>" . print_r($bankTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the project or asset or empty.
        echo "Dim3 (string): " . Util::objectToStr($bankTransactionLine->getDim3()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "FreeChar: {$bankTransactionLine->getFreeChar()}<br />";                                                                                       // string|null                  Free character field.
        echo "ID: {$bankTransactionLine->getID()}<br />";                                                                                                   // int|null                     Line ID.
        echo "LineType: {$bankTransactionLine->getLineType()}<br />";                                                                                       // LineType|null                Line type.
        echo "MatchLevel: {$bankTransactionLine->getMatchLevel()}<br />";                                                                                   // int|null                     Only if line type is detail. The level of the matchable dimension. Read-only attribute.
        echo "MatchStatus: {$bankTransactionLine->getMatchStatus()}<br />";                                                                                 // MatchStatus|null             Payment status of the bank transaction. If line type total or vat always notmatchable. Read-only attribute.

        if ($bankTransactionLine->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($bankTransactionLine->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
        }

        echo "PerformanceCountry (\\PhpTwinfield\\Country): <pre>" . print_r($bankTransactionLine->getPerformanceCountry(), true) . "</pre><br />";         // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
        echo "PerformanceCountry (string): " . Util::objectToStr($bankTransactionLine->getPerformanceCountry()) . "<br />";                                 // string|null
        echo "PerformanceDate (\\DateTimeInterface): <pre>" . print_r($bankTransactionLine->getPerformanceDate(), true) . "</pre><br />";                   // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
        echo "PerformanceDate (string): " . Util::formatDate($bankTransactionLine->getPerformanceDate()) . "<br />";                                        // string|null
        echo "PerformanceType: {$bankTransactionLine->getPerformanceType()}<br />";                                                                         // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
        echo "PerformanceVatNumber: {$bankTransactionLine->getPerformanceVatNumber()}<br />";                                                               // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
        echo "Rate: {$bankTransactionLine->getRate()}<br />";                                                                                               // float|null                   The exchange rate used for the calculation of the base amount.
        echo "Reference (\\PhpTwinfield\\MatchReference): <pre>" . print_r($bankTransactionLine->getReference(), true) . "</pre><br />";                    // MatchReference|null          Match reference
        echo "Relation: {$bankTransactionLine->getRelation()}<br />";                                                                                       // int|null                     Only if line type is detail. Read-only attribute.
        echo "RepRate: {$bankTransactionLine->getRepRate()}<br />";                                                                                         // float|null                   The exchange rate used for the calculation of the reporting amount.
        echo "RepValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getRepValue(), true) . "</pre><br />";                                      // Money|null                   Amount in the reporting currency.
        echo "RepValue (string): " . Util::formatMoney($bankTransactionLine->getRepValue()) . "<br />";                                                     // string|null
        echo "RepValueOpen (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getRepValueOpen(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. The amount still owed in reporting currency. Read-only attribute.
        echo "RepValueOpen (string): " . Util::formatMoney($bankTransactionLine->getRepValueOpen()) . "<br />";                                             // string|null
        echo "Result: {$bankTransactionLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "SignedValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getSignedValue(), true) . "</pre><br />";                                // Money|null
        echo "SignedValue (string): " . Util::formatMoney($bankTransactionLine->getSignedValue()) . "<br />";                                               // string|null
        echo "Value (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getValue(), true) . "</pre><br />";                                            // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
        echo "Value (string): " . Util::formatMoney($bankTransactionLine->getValue()) . "<br />";                                                           // string|null
        echo "VatBaseTotal (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatBaseTotal(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The total VAT amount in base currency.
        echo "VatBaseTotal (string): " . Util::formatMoney($bankTransactionLine->getVatBaseTotal()) . "<br />";                                             // string|null
        echo "VatBaseTurnover (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatBaseTurnover(), true) . "</pre><br />";                        // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
        echo "VatBaseTurnover (string): " . Util::formatMoney($bankTransactionLine->getVatBaseTurnover()) . "<br />";                                       // string|null
        echo "VatBaseValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatBaseValue(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. VAT amount in base currency.
        echo "VatBaseValue (string): " . Util::formatMoney($bankTransactionLine->getVatBaseValue()) . "<br />";                                             // string|null
        echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($bankTransactionLine->getVatCode(), true) . "</pre><br />";                               // VatCode|null                 Only if line type is detail or vat. VAT code.
        echo "VatCode (string): " . Util::objectToStr($bankTransactionLine->getVatCode()) . "<br />";                                                       // string|null
        echo "VatRepTotal (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatRepTotal(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepTotal (string): " . Util::formatMoney($bankTransactionLine->getVatRepTotal()) . "<br />";                                               // string|null
        echo "VatRepTurnover (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatRepTurnover(), true) . "</pre><br />";                          // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
        echo "VatRepTurnover (string): " . Util::formatMoney($bankTransactionLine->getVatRepTurnover()) . "<br />";                                         // string|null
        echo "VatRepValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatRepValue(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepValue (string): " . Util::formatMoney($bankTransactionLine->getVatRepValue()) . "<br />";                                               // string|null
        echo "VatTotal (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatTotal(), true) . "</pre><br />";                                      // Money|null                   Only if line type is total. The total VAT amount in the currency of the bank transaction.
        echo "VatTotal (string): " . Util::formatMoney($bankTransactionLine->getVatTotal()) . "<br />";                                                     // string|null
        echo "VatTurnover (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatTurnover(), true) . "</pre><br />";                                // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the bank transaction.
        echo "VatTurnover (string): " . Util::formatMoney($bankTransactionLine->getVatTurnover()) . "<br />";                                               // string|null
        echo "VatValue (\\Money\\Money): <pre>" . print_r($bankTransactionLine->getVatValue(), true) . "</pre><br />";                                      // Money|null                   Only if line type is detail. VAT amount in the currency of the bank transaction.
        echo "VatValue (string): " . Util::formatMoney($bankTransactionLine->getVatValue()) . "<br />";                                                     // string|null
    }
}

// Copy an existing BankTransaction to a new entity
if ($executeCopy) {
    try {
        $bankTransaction = $transactionApiConnector->get(\PhpTwinfield\BankTransaction::class, "BNK", 201900011, $office);
    } catch (ResponseException $e) {
        $bankTransaction = $e->getReturnedObject();
    }

    $bankTransaction->setNumber(null);

    //Optional, but recommended so your copy is not posted to final immediately
    $bankTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());

    try {
        $bankTransactionCopy = $transactionApiConnector->send($bankTransaction);
    } catch (ResponseException $e) {
        $bankTransactionCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($bankTransactionCopy);
    echo "</pre>";

    echo "Result of copy process: {$bankTransactionCopy->getResult()}<br />";
    echo "Number of copied BankTransaction: {$bankTransactionCopy->getNumber()}<br />";
}

// Create a new BankTransaction from scratch, alternatively read an existing BankTransaction as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $bankTransaction = new \PhpTwinfield\BankTransaction;

    // Required values for creating a new BankTransaction
    $bankTransaction->setCode('BNK');                                                                                                                       // string|null                  Transaction type code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $bankTransaction->setCurrency($currency);                                                                                                               // Currency|null                Currency code.
    //$bankTransaction->setCurrency(\PhpTwinfield\Currency::fromCode("EUR"));                                                                               // string|null
    $bankTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());                                                                                 // Destiny|null                 Attribute to indicate the destiny of the bank transaction. Only used in the request XML. temporary = bank transaction will be saved as provisional.
    //$bankTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::FINAL());                                                                                   // Destiny|null                 final = bank transaction will be saved as final.
    $bankTransaction->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $bankTransaction->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null

    // Optional values for creating a new BankTransaction
    $bankTransaction->setAutoBalanceVat(true);                                                                                                              // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    $date = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    $bankTransaction->setDate($date);                                                                                                                       // DateTimeInterface|null       Transaction date. Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    $bankTransaction->setDate(Util::parseDate("20190901"));                                                                                                 // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    $bankTransaction->setDateRaiseWarning(false);                                                                                                           // bool|null
    //$bankTransaction->setFreeText1('Example FreeText 1');                                                                                                 // string|null                  Free text field 1 as entered on the transaction type.
    //$bankTransaction->setFreeText2('Example FreeText 2');                                                                                                 // string|null                  Free text field 2 as entered on the transaction type.
    //$bankTransaction->setFreeText3('Example FreeText 3');                                                                                                 // string|null                  Free text field 3 as entered on the transaction type.
    //$bankTransaction->setNumber(201900011);                                                                                                               // int|null                     Transaction number. When creating a new bank transaction, don't include this tag as the transaction number is determined by the system. When updating a bank transaction, the related transaction number should be provided.
    //$bankTransaction->setPeriod("2019/07");                                                                                                               // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    $bankTransaction->setRaiseWarning(true);                                                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    //$bankTransactionLine->setStartValue(\Money\Money::EUR(1000));                                                                                         // Money|null                   Opening balance. If not provided, the opening balance is set to zero. (Equals 10.00 EUR)
    $bankTransaction->setStatementNumber(20);                                                                                                               // int|null                     Number of the bank statement. Don't confuse this number with the transaction number.

    // The minimum amount of BankTransactionLines linked to an BankTransaction object is 2 (1 total, 1 detail)
    $bankTransactionLine1 = new \PhpTwinfield\BankTransactionLine;
    $bankTransactionLine1->setLineType(\PhpTwinfield\Enums\LineType::TOTAL());                                                                              // LineType|null                Line type.

    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('1010');
    $bankTransactionLine1->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the bank balance account. If line type = detail the customer or supplier balance account or profit and loss account.
    $bankTransactionLine1->setDim1(\PhpTwinfield\GeneralLedger::fromCode('1010'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $bankTransactionLine1->setID(1);                                                                                                                        // int|null            	        Line ID.
    $bankTransactionLine1->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
    //$bankTransactionLine1->setVatBaseTotal(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is total. The total VAT amount in base currency.
    //$bankTransactionLine1->setVatRepTotal(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
    //$bankTransactionLine1->setVatTotal(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is total. The total VAT amount in the currency of the bank transaction.

    $bankTransaction->addLine($bankTransactionLine1);                                                                                                       // BankTransactionLine          Add a BankTransactionLine object to the BankTransaction object

    $bankTransactionLine2 = new \PhpTwinfield\BankTransactionLine;
    $bankTransactionLine2->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $bankTransactionLine2->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $bankTransactionLine2->setDescription("Example Description on line with ID 2");                                                                         // string|null          	    Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $bankTransactionLine2->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the bank balance account. If line type = detail the customer or supplier balance account or profit and loss account.
    $bankTransactionLine2->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $bankTransactionLine2->setID(2);                                                                                                                        // int|null            	        Line ID.
    $bankTransactionLine2->setValue(\Money\Money::EUR(-10000));                                                                                              // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.

    //$bankTransactionLine2->setComment('Example Comments');                                                                                                // string|null                  Comment set on the transaction line.
    $currencyDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$bankTransaction->setCurrencyDate($currencyDate);                                                                                                     // DateTimeInterface|null       Only if line type is detail. The line date. Only allowed if the line date in the bank book is set to Allowed or Mandatory.
    //$bankTransaction->setCurrencyDate(Util::parseDate("20190901"));                                                                                       // string|null
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('1234');
    //$bankTransaction->setDestOffice($destOffice);                                                                                                         // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
    //$bankTransaction->setDestOffice(\PhpTwinfield\Office::fromCode('1234'));                                                                              // string|null
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1001');
    //$bankTransactionLine2->setDim2($dim2);                                                                                                                // object|null                  If line type = total empty. If line type = detail the customer or supplier or the cost center or empty. If line type = vat empty.
    //$bankTransactionLine2->setDim2(\PhpTwinfield\Customer::fromCode('1001'));                                                                             // string|null
    $dim3 = new \PhpTwinfield\Project;
    $dim3->setCode('P0000');
    //$bankTransactionLine2->setDim3($dim3);                                                                                                                // object|null                  If line type = total empty. If line type = detail the project or asset or empty. If line type = vat empty.
    //$bankTransactionLine2->setDim3(\PhpTwinfield\Project::fromCode('P0000'));                                                                             // string|null
    //$bankTransactionLine2->setFreeChar('A');                                                                                                              // string|null                  Free character field.
    $performanceCountry = new \PhpTwinfield\Country;
    $performanceCountry->setCode('NL');
    //$bankTransactionLine2->setPerformanceCountry($performanceCountry);                                                                                    // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
    //$bankTransactionLine2->setPerformanceCountry(\PhpTwinfield\Country::fromCode('NL'));                                                                  // string|null
    $performanceDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$bankTransactionLine2->setPerformanceDate($performanceDate);                                                                                          // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
    //$bankTransactionLine2->setPerformanceDate(Util::parseDate("20190901"));                                                                               // string|null
    //$bankTransactionLine2->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                           // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
    //$bankTransactionLine2->setPerformanceVatNumber('NL1234567890B01');                                                                                    // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
    //$bankTransactionLine2->setRepValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Amount in the reporting currency.
    //$bankTransactionLine2->setRate(1);                                                                                                                    // float|null                   The exchange rate used for the calculation of the base amount.
    //$bankTransactionLine2->setRepRate(1);                                                                                                                 // float|null                   The exchange rate used for the calculation of the reporting amount.
    //$bankTransactionLine2->setVatBaseValue(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is detail. VAT amount in base currency.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VN');
    //$bankTransactionLine2->setVatCode($vatCode);                                                                                                          // VatCode|null                 Only if line type is detail or vat. VAT code.
    //$bankTransactionLine2->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));                                                                             // string|null
    //$bankTransactionLine2->setVatRepValue(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is detail. VAT amount in reporting currency.
    //$bankTransactionLine2->setVatValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is detail. VAT amount in the currency of the bank transaction.

    $bankTransaction->addLine($bankTransactionLine2);

    //$bankTransactionLine3 = new \PhpTwinfield\BankTransactionLine;
    //$bankTransactionLine3->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Line type.
    //$bankTransactionLine3->setVatBaseTurnover(\Money\Money::EUR(-10000));                                                                                 // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
    //$bankTransactionLine3->setVatRepTurnover(\Money\Money::EUR(-10000));                                                                                  // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
    //$bankTransactionLine3->setVatTurnover(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the bank transaction.

    try {
        $bankTransactionNew = $transactionApiConnector->send($bankTransaction);
    } catch (ResponseException $e) {
        $bankTransactionNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($bankTransactionNew);
    echo "</pre>";

    echo "Result of creation process: {$bankTransactionNew->getResult()}<br />";
    echo "Number of new BankTransaction: {$bankTransactionNew->getNumber()}<br />";
}

// Delete a BrankTransaction based off the passed in office, code, number and given reason
if ($executeDelete) {
    $bookingReference = new \PhpTwinfield\BookingReference($office, 'BNK', 201900026);

    try {
        $bankTransactionDeleted = $transactionApiConnector->delete($bookingReference, 'Example reason');
    } catch (ResponseException $e) {
        $bankTransactionDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($bankTransactionDeleted);
    echo "</pre>";
}
