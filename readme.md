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

### Authentication
You need to set up a `\PhpTwinfield\Secure\AuthenticatedConnection` class with your credentials.

#### Session Login
**:warning: Note that Twinfield has stated that session login is deprecated and will be removed. End of life date will be announced later. See https://c3.twinfield.com/webservices/documentation/#/ApiReference/Authentication/WebServices**

When using basic username and password authentication, the `\PhpTwinfield\Secure\WebservicesAuthentication` class should be used, as follows:

```php
$connection = new Secure\WebservicesAuthentication("username", "password", "organization");
```

#### OAuth2
In order to use OAuth2 to authenticate with Twinfield, one should use the `\PhpTwinfield\Secure\Provider\OAuthProvider` to retrieve an `\League\OAuth2\Client\Token\AccessToken` object, and extract the refresh token from this object. Furthermore, it is required to set up a default `\PhpTwinfield\Office`, that will be used during requests to Twinfield. **Please note:** when a different office is specified when sending a request through one of the `ApiConnectors`, this Office will override the default.

##### Request a client ID/Secret from Twinfield
Go to the [Twinfield web site](https://www.twinfield.nl/openid-connect-request/) in order to register your OpenID Connect / OAuth 2.0 client and get your Client ID and Secret. Fill in your personal information en pick the following:
* Flow: Authorization Code
* Consent: Your choice
* Redirect URL: The full URI where the following code is available on your domain/server
* Add more redirect URL's?: Your choice
* Post logout URL: Your choice, can be left blank
* Add more post logout URL's?: Your choice

##### Grant Authorisation and retrieve intitial Access Token
On loading a page containing the following code the user will be redirected to the Twinfield Login page.
After succesfull login and optionally consent (see above) the user will be redirected back to the page at which point the Access Token and Refresh Token can be retrieved.

For more information, please refer to: https://github.com/thephpleague/oauth2-client#usage

```php
$provider = new \PhpTwinfield\Secure\Provider\OAuthProvider([
    'clientId'                => 'someClientId',            // The Client ID assigned to you by Twinfield
    'clientSecret'            => 'someClientSecret',        // The Client Secret assigned to you by Twinfield
    'redirectUri'             => 'https://example.org/',    // The full URL your filled in at Redirect URL when you requested your client ID
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {
    //Optionally limit your scope if you don't require all.
    $options = [
        'scope' => ['twf.user','twf.organisation','twf.organisationUser','offline_access','openid']
    ];

    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');
} else {
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        //Twinfield's Refresh Token is valid for 550 days.
        //Remember to put in place functionality to request the user to renew their authorization.
        //This can be done by requesting the user to reload this page and logging into Twinfield
        //before the refresh token is invalidated after 550 days.
        $refresh_expiry = strtotime(date('Ymd') . " +550 days");

        //Save Refresh Token and Refresh Token Expiry Time to storage
        $refreshTokenStorage                      = array();
        $refreshTokenStorage['refresh_token']     = $accessToken->getRefreshToken();
        $refreshTokenStorage['refresh_expiry']    = $refresh_expiry;

        SaveRefreshTokenToStore($refreshTokenStorage);

        //OPTIONAL: Save Access Token, Access Token Expiry Time and Cluster to storage
        $validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
        $validationResult = @file_get_contents($validationUrl . urlencode($accessToken->getToken()));

        if ($validationResult !== false) {
            $resultDecoded                    = \json_decode($validationResult, true);
            $accessTokenStorage                     = array();
            $accessTokenStorage['access_token']     = $accessToken->getToken();
            $accessTokenStorage['access_expiry']    = $accessToken->getExpires();
            $accessTokenStorage['access_cluster']   = $resultDecoded["twf.clusterUrl"];
            SaveAccessTokenToStore($accessTokenStorage);
        }
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        // Failed to get the access token or user details.
        exit($e->getMessage());
    }
}
```

##### Optionally: Store a valid access token and cluster through a sceduled task/cron job running in the background
Running the following code every 60 minutes (or a bit less as Access Tokens are valid for exactly 60 minutes) will reduce connection time when working with the Api (by about 2 seconds). It will also reduce connection load on Twinfield when making more than 20-30 connections/day.

```php
$refreshTokenStorage = retrieveRefreshTokenFromStore();

$provider    = new OAuthProvider([
    'clientId'     => 'someClientId',
    'clientSecret' => 'someClientSecret',
    'redirectUri'  => 'https://example.org/'
]);

$accessToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshTokenStorage['refresh_token']
]);

$validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
$validationResult = @file_get_contents($validationUrl . urlencode($accessToken->getToken()));

if ($validationResult !== false) {
    $resultDecoded                    = \json_decode($validationResult, true);

    $tokenStorage                     = array();
    $tokenStorage['access_token']     = $accessToken->getToken();
    $tokenStorage['access_expiry']    = $accessToken->getExpires();
    $tokenStorage['access_cluster']   = $resultDecoded["twf.clusterUrl"];

    SaveAccessTokenToStore($tokenStorage);
}
```

##### Connection
Using the stored Refresh Token and optionally Access Token/Cluster, we can create an instance of the `\PhpTwinfield\Secure\OpenIdConnectAuthentication` class, as follows:

```php
$provider    = new OAuthProvider([
    'clientId'     => 'someClientId',
    'clientSecret' => 'someClientSecret',
    'redirectUri'  => 'https://example.org/'
]);

//Retrieve Refresh Token and Refresh Token Expiry Time from storage
$refreshTokenStorage = retrieveRefreshTokenFromStore();

//OPTIONAL: Retrieve Access Token, Access Token Expiry Time and Cluster from storage
$accessTokenStorage = retrieveAccessTokenFromStore();

$office       = \PhpTwinfield\Office::fromCode("someOfficeCode");

if ($accessTokenStorage['access_expiry'] > time()) {
    $connection  = new \PhpTwinfield\Secure\OpenIdConnectAuthentication($provider, $refreshTokenStorage['refresh_token'], $office, $accessTokenStorage['access_token'], $accessTokenStorage['access_cluster']);
} else {
    $connection  = new \PhpTwinfield\Secure\OpenIdConnectAuthentication($provider, $refreshTokenStorage['refresh_token'], $office)
}
```

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

### Browse data
In order to get financial data out of Twinfield like general ledger transactions, sales invoices, and so on, you can use the the browse data functionality.
More information about the browse data functionality in Twinfield can be found in the [documentation](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData).

#### Browse definition

You can retrieve the browse definition of a browse code as follows.
You don't need to retrieve the browse definition for getting the browse data. It's only for viewing the browse definition of a browse code to know exactly which columns are available.

```php
$connector = new BrowseDataApiConnector($connection);
$browseDefinition = $connector->getBrowseDefinition('000');
```

#### Browse fields

You can retrieve the browse fields as follows.
You don't need to retrieve the browse fields for getting the browse data. It's only for viewing the definitions of all browse fields so you now what you can expect when retrieving browse data.

```php
$connector = new BrowseDataApiConnector($connection);
$browseFields = $connector->getBrowseFields();
```

#### Browse data

You can retrieve browse data of a browse code as follows.

```php
$connector = new BrowseDataApiConnector($connection);

// First, create the columns that you want to retrieve (see the browse definition for which columns are available)
$columns[] = (new BrowseColumn())
    ->setField('fin.trs.head.yearperiod')
    ->setLabel('Period')
    ->setVisible(true)
    ->setAsk(true)
    ->setOperator(Enums\BrowseColumnOperator::BETWEEN())
    ->setFrom('2013/01')
    ->setTo('2013/12');

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.head.code')
    ->setLabel('Transaction type')
    ->setVisible(true);

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.head.shortname')
    ->setLabel('Name')
    ->setVisible(true);

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.head.number')
    ->setLabel('Trans. no.')
    ->setVisible(true);

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.line.dim1')
    ->setLabel('General ledger')
    ->setVisible(true)
    ->setAsk(true)
    ->setOperator(Enums\BrowseColumnOperator::BETWEEN())
    ->setFrom('1300')
    ->setTo('1300');

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.head.curcode')
    ->setLabel('Currency')
    ->setVisible(true);

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.line.valuesigned')
    ->setLabel('Value')
    ->setVisible(true);

$columns[] = (new BrowseColumn())
    ->setField('fin.trs.line.description')
    ->setLabel('Description')
    ->setVisible(true);

// Second, create sort fields
$sortFields[] = new BrowseSortField('fin.trs.head.code');

// Get the browse data
$browseData = $connector->getBrowseData('000', $columns, $sortFields);
```

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

## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)
* [Alex Jeensma](http://vontis.nl/)
