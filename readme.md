# Twinfield  [![Build Status](https://travis-ci.org/php-twinfield/twinfield.svg?branch=master)](http://travis-ci.org/php-twinfield/twinfield)
A PHP library for Twinfield Integration.
Use the Twinfield SOAP Services to have your PHP application communicate directly with your Twinfield account.

## Installation

Install this Twinfield PHP library with Composer:

```bash
composer require 'php-twinfield/twinfield:^2.0'
```


## Usage

### Authentication
You need to set up a `\PhpTwinfield\Secure\AuthenticatedConnection` class with your credentials. When using basic 
username and password authentication, the `\PhpTwinfield\Secure\WebservicesAuthentication` class should be used, as follows:

```php
$connection = new Secure\WebservicesAuthentication("username", "password", "organization");
```

In order to use OAuth2 to authenticate with Twinfield, one should use the `\PhpTwinfield\Secure\Provider\OAuthProvider` to retrieve an `\League\OAuth2\Client\Token\AccessToken` object, and extract the refresh token from this object. Furthermore, it is required to set up a default `\PhpTwinfield\Office`, that will be used during requests to Twinfield. **Please note:** when a different office is specified when sending a request through one of the `ApiConnectors`, this Office will override the default.

Using this information, we can create an instance of the `\PhpTwinfield\Secure\OpenIdConnectAuthentication` class, as follows:

```php
$provider    = new OAuthProvider([
    'clientId'     => 'someClientId',
    'clientSecret' => 'someClientSecret',
    'redirectUri'  => 'https://example.org/'
]);
$accessToken  = $provider->getAccessToken("authorization_code", ["code" => ...]);
$refreshToken = $accessToken->getRefreshToken();
$office       = \PhpTwinfield\Office::fromCode("someOfficeCode");

$connection  = new \PhpTwinfield\Secure\OpenIdConnectAuthentication($provider, $refreshToken, $office);
```
For more information about retrieving the initial `AccessToken`, please refer to: https://github.com/thephpleague/oauth2-client#usage

### Getting data from the API
In order to communicate with the Twinfield API, you need to create an `ApiConnector` instance for the corresponding
resource and use the `get()` or `list()` method.

The `ApiConnector` takes a `Secure\AuthenticatedConnection` object:  

An example:

```php

$connection = new Secure\WebservicesAuthentication("username", "password", "organization");
$customerApiConnector = new ApiConnectors\CustomerApiConnector($connection);

// Get one customer.
$office   = Office::fromCode('office code');
$customer = $customerApiConnector->get('1001', $office);

// Get a list of all customers.
$customer = $customerApiConnector->listAll($office);
```

### Creating or updating objects
If you want to create or update a customer or any other object, it's just as easy:

```php
$customer_factory = new ApiConnectors\CustomerApiConnector($connection);

// First, create the objects you want to send.
$customer = new Customer();
$customer
    ->setCode('1001')
    ->setName('John Doe')
    ->setOffice($office)
    ->setEBilling(false);

$customer_address = new CustomerAddress();
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

You can also send multiple objects in one batch, chunking is handled automatically. 

### Supported resources
Not all resources from the Twinfield API are currently implemented. Feel free to create a pull request when you need
support for another resource.

| Component                                                                                                       | get()              | listAll()          | send()             | delete()           |  Mapper             |
| --------------------------------------------------------------------------------------------------------------- | :----------------: | :----------------: | :----------------: | :----------------: | :----------------: |
| [Articles](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles)                  | :white_check_mark: |                    | :white_check_mark: |                    | :white_check_mark: |
| [BankTransaction](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions)|                  |                    | :white_check_mark: | :white_check_mark: |                    |
| [Customer](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers)                 | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: |
| [Electronic Bank Statements](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements)|         |                    | :white_check_mark: |                    |                    |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)               | :white_check_mark: |                    | :white_check_mark: |                    | :white_check_mark: |
| [Matching](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching)            |                    |                    | :white_check_mark: |                    | :white_check_mark: |                    |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                    |                    | :white_check_mark: |                    |                    |                    |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: |
| Transactions:<br> [Purchase](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions), [Sale](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions), [Journal](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions) | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |
| [Users](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users)                        |                    | :white_check_mark: |                    |                    |                    |
| [Vat types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT)                      |                    | :white_check_mark: |                    |                    |                    |
 

## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)
* [Alex Jeensma](http://vontis.nl/)
