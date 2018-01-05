<?php

require 'vendor/autoload.php';

/**
 * get value json storage.
 */
function getValue($key)
{
    $storage = json_decode(file_get_contents('storage.json'), true);
    if (array_key_exists($key, $storage)) {
        return $storage[$key];
    }

    return;
}
/**
 * set value to json storage.
 */
function setValue($key, $value)
{
    $storage = json_decode(file_get_contents('storage.json'), true);
    $storage[$key] = $value;
    file_put_contents('storage.json', json_encode($storage));
}

if (isset($_GET['code'])) {
    setValue('authorizationcode', $_GET['code']);
}

// setup connection
function getConnection()
{
    $connection = new \PhpTwinfield\Secure\Connection();
    $connection->setRedirectUrl('YOUR REDIRECT URL');
    $connection->setClientId('YOUR CLIENT ID');
    $connection->setClientSecret('YOUR CLIENT SECRET');
    $connection->setOffice('YOUR OFFICE CODE (IE NL123456)');

    if (getValue('authorizationcode')) {
        $connection->setAuthorizationCode(getValue('authorizationcode'));
    }

    if (getValue('accesstoken')) { // retrieves accesstoken from database
        $connection->setAccessToken(getValue('accesstoken'));
    }

    if (getValue('refreshtoken')) { // retrieves refreshtoken from database
        $connection->setRefreshToken(getValue('refreshtoken'));
    }

    if (getValue('expires_in')) {  // retrieves expires timestamp from database
        $connection->setTokenExpires(getValue('expires_in'));
    }

    return $connection;
}

// do redirect for auth
function doRedirect()
{
    $connection = getConnection();
    $connection->redirectForAuthorization();
}

$connection = getConnection();
$connection->connect();

setValue('accesstoken', $connection->getAccessToken());
setValue('refreshtoken', $connection->getRefreshToken());
setValue('expires_in', $connection->getTokenExpires());

// get office
$office = \PhpTwinfield\Office::fromCode('NL123456');

// get a list of all customers.
$customerApiConnector = new \PhpTwinfield\ApiConnectors\CustomerApiConnector($connection);
$customerList = $customerApiConnector->listAll($office);

var_dump($customerList);
