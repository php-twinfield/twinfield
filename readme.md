# Twinfield  [![Build Status](https://travis-ci.org/php-twinfield/twinfield.svg?branch=master)](http://travis-ci.org/php-twinfield/twinfield)
A PHP library for Twinfield Integration.
Use the Twinfield SOAP Service to have your PHP application communicate directly with your Twinfield account.

> :warning: This library is still under construction. [Version 1](https://github.com/php-twinfield/twinfield/tree/release-1.0) is available from Composer, but we recommend you to wait for (or help with!) version 2. We hope to release it early 2018.

---


## Installation

Install this Twinfield PHP library with Composer:

```bash
composer require php-twinfield/twinfield
```


## Usage

### Authentication
You need to set up a `\PhpTwinfield\Secure\Config` class with your credentials. An example using basic username and
password authentication:

```php
$config = new \PhpTwinfield\Secure\Config();
$config->setCredentials('Username', 'Password', 'Organization', 'Office');
```

Another example, using OAuth:

```php
$config = new \PhpTwinfield\Secure\Config();

// The true parameter at the end tells the system to automatically redirect to twinfield to login.
$config->setOAuthParameters('clientID', 'clientSecret', 'returnURL', 'Organization', 'Office', true);
```

### Getting data from the API
In order to communicate with the Twinfield API, you need to create an `ApiConnector` instance for the corresponding
resource and use the `get()` or `list()` method.

An example:

```php
$customerApiConnector = new \PhpTwinfield\ApiConnectors\CustomerApiConnector($config);

// Get one customer.
$customer = $customerApiConnector->get('1001');

// Get a list of all customers.
$customer = $customerApiConnector->listAll();
```

### Creating or updating objects
If you want to create or update a customer or any other object, it's just as easy:

```php
$customer_factory = new \PhpTwinfield\ApiConnectors\CustomerApiConnector($config);

// First, create the objects you want to send.
$customer = new \PhpTwinfield\Customer();
$customer
    ->setCode('1001')
    ->setName('John Doe')
    ->setType('DEB')
    ->setEBilling(false);

$customer_address = new \PhpTwinfield\CustomerAddress();
$customer_address
    ->setType('invoice')
    ->setDefault(false)
    ->setPostcode('1212 AB')
    ->setCity('TestCity')
    ->setCountry('NL')
    ->setTelephone('010-12345')
    ->setFax('010-1234')
    ->setEmail('johndoe@example.com');
$customer->addAddress($customer_address);

// And secondly, send it to Twinfield.
$customer_factory->send($customer);
```

### Supported resources
Not all resources from the Twinfield API are currently implemented. Feel free to create a pull request when you need
support for another resource.

| Component                                                                                                       | get()              | listAll()          | send()             | Mapper             |
| --------------------------------------------------------------------------------------------------------------- | :----------------: | :----------------: | :----------------: | :----------------: |
| [Articles](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles)                  | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: |
| [Customer](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers)                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)               | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                    |                    | :white_check_mark: |                    |                    |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| Transactions:<br> [Purchase](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions), [Sale](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions), [Journal](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions) | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: |
| [Users](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users)                        |                    | :white_check_mark: |                    |                    |
| [Vat types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT)                      |                    | :white_check_mark: |                    |                    |


## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)


## License

Copyright 2009-2013 Pronamic.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
