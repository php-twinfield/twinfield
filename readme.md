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

| Component                                                                                                                            | get()              | listAll()          | send()             | delete()           |  Mapper            |
| ---------------------------------------------------------------------------------------------------------------                      | :----------------: | :----------------: | :----------------: | :----------------: | :----------------: |
| [Activities](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities)                                   | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Articles](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles)                                       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Asset Methods](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods)                              | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Browse Data](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData)                                  | :white_check_mark: |                    |                    |                    | :white_check_mark: |
| Cash and Bank books                                                                                                                  |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| [Cost Centers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/CostCenters)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| Countries                                                                                                                            |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| [Currencies](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies)                                   |                    | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: |
| [Customers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers)                                     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Dimension Groups](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups)                        | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Dimension Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes)                          | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: |
| [Electronic Bank Statements](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements)|         |                    | :white_check_mark: |                    |                    |                    |
| [Fixed Assets](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [General Ledger Accounts](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets)                   | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Matching](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching)                                 |                    |                    | :white_check_mark: |                    | :white_check_mark: |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                                         | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: |
| Paycodes                                                                                                                             |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| [Projects](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects)                                       | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Rates](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates)                                             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: |
| [Sales Invoice Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                               |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                                     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| Transactions:<br> [Purchase](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions), [Sale](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions), [Journal](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions), [Cash](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions), [Bank](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions) | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Users](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users)                                             | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: |
| User Roles                                                                                                                           |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| [VAT](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT)                                                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| VAT Groups                                                                                                                           |                    | :white_check_mark: |                    |                    | :white_check_mark: |
| VAT Groups Countries                                                                                                                 |                    | :white_check_mark: |                    |                    | :white_check_mark: |

## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)
* [Alex Jeensma](http://vontis.nl/)
