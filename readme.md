# Twinfield  [![Build Status](https://travis-ci.org/php-twinfield/twinfield.svg?branch=master)](http://travis-ci.org/php-twinfield/twinfield)
A PHP library for Twinfield Integration.
Use the Twinfield SOAP Services to have your PHP application communicate directly with your Twinfield account.

**:warning: Note that this libary is *not* created or mainained by Twinfield. You can only get support on the code in this library here. For any questions related to your Twinfield administration or how to do certain things with the Twinfield API, contact your Twinfield account manager.**

## Installation

Install this Twinfield PHP library with Composer:

```bash
composer require 'php-twinfield/twinfield:^2.0'
```


## Usage

See [Usage](usage.md)

### Supported resources
Not all resources from the Twinfield API are currently implemented. Feel free to create a pull request when you need
support for another resource.

| Component                                                                                                                            | get()              | listAll()          | send()             | delete()           |  Mapper            |  Example                                            |
| ---------------------------------------------------------------------------------------------------------------                      | :----------------: | :----------------: | :----------------: | :----------------: | :----------------: | :----------------:                                  |
| [Activities](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities)                                   | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Activity](examples/Activity.php)                   |
| [Articles](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles)                                       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Articles](examples/Activity.php)                   |
| [Asset Methods](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods)                              | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Asset Methods](examples/Activity.php)              |
| [Browse Data](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData)                                  | :white_check_mark: |                    |                    |                    | :white_check_mark: | [Browse Data](examples/Activity.php)                |
| Cash and Bank books                                                                                                                  |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Cash and Bank books](examples/Activity.php)        |
| [Cost Centers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/CostCenters)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Cost Centers](examples/Activity.php)               |
| Countries                                                                                                                            |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Countries](examples/Activity.php)                  |
| [Currencies](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies)                                   | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Currencies](examples/Activity.php)                 |
| [Customers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers)                                     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Customers](examples/Activity.php)                  |
| [Dimension Groups](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups)                        | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Dimension Groups](examples/Activity.php)           |
| [Dimension Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes)                          | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: | [Dimension Types](examples/Activity.php)            |
| [Electronic Bank Statements](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements)|         |                    | :white_check_mark: |                    |                    |                    |  |
| [Fixed Assets](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Fixed Assets](examples/Activity.php)               |
| [General Ledger Accounts](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets)                   | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [General Ledger Accounts](examples/Activity.php)    |
| [Matching](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching)                                 |                    |                    | :white_check_mark: |                    | :white_check_mark: |  |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                                         | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: | [Offices](examples/Office.php)                      |
| Paycodes                                                                                                                             |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Paycodes](examples/PayCode.php)                    |
| [Projects](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects)                                       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Projects](examples/Project.php)                    |
| [Rates](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates)                                             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Rates](examples/Rate.php)                          |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: | [Sales Invoices](examples/Invoice.php)              |
| [Sales Invoice Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                               |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Sales Invoice Types](examples/InvoiceType.php)     |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                                     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Suppliers](examples/Activity.php)                  |
| Transactions:<br>
[Bank](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions),
[Cash](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions),
[Journal](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions),
[Purchase](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions),
[Sale](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions)                                            | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Transactions](examples/Transactions.php)           |
| [Users](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users)                                             | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: | [Users](examples/Users.php)                         |
| User Roles                                                                                                                           |                    | :white_check_mark: |                    |                    | :white_check_mark: | [User Roles](examples/Activity.php)                 |
| [VAT](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT)                                                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [VAT](examples/VatCode.php)                         |
| VAT Groups                                                                                                                           |                    | :white_check_mark: |                    |                    | :white_check_mark: | [VAT Groups](examples/VatGroup.php)                 |
| VAT Group Countries                                                                                                                  |                    | :white_check_mark: |                    |                    | :white_check_mark: | [VAT Group Countries](examples/VatGroupCountry.php) |

## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)
* [Alex Jeensma](http://vontis.nl/)
