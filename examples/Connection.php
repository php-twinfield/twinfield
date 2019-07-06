<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

/* In order to use OAuth2 to authenticate with Twinfield, one should use the \PhpTwinfield\Secure\Provider\OAuthProvider
 * to retrieve an \League\OAuth2\Client\Token\AccessToken object, and extract the refresh and access token from this object.
 * Furthermore, it is required to set up a default \PhpTwinfield\Office, that will be used during requests to Twinfield.
 * Please note: when a different office is specified when sending a request through one of the ApiConnectors, this office will override the default.
 */

function RetrieveRefreshTokenFromStore() {
    // Retrieve refresh token and optionally expiry time from some kind of storage (e.g. SQL database).

    $refreshTokenStorage                       = array();
    $refreshTokenStorage['refresh_token']      = 'SavedRefreshToken';

    // Optional
    $refreshTokenStorage['refresh_expiry']     = 'SavedRefreshExpiryTimeStamp';

    return $refreshTokenStorage;
}

function RetrieveAccessTokenFromStore() {
    // Retrieve access token, expiry time and cluster from some kind of storage (e.g. SQL database).

    $accessTokenStorage                         = array();
    $accessTokenStorage['access_token']         = 'SavedRefreshToken';
    $accessTokenStorage['access_expiry']        = 'SavedAccessExpiryTimeStamp';
    $accessTokenStorage['access_cluster']       = 'SavedAccessCluster';

    return $accessTokenStorage;
}

require_once('vendor/autoload.php');

// The client ID assigned to you by Twinfield.
$twin_client_id = 'SomeClientId';

// The client secret assigned to you by Twinfield.
$twin_client_secret = 'SomeClientSecret';

// The FQDN URI of the script used for initial authorization, must be filled out on the form (Redirect URL) when requesting the client ID/Secret from Twinfield.
$twin_redirect_uri = 'https://example.org/twinfield/Authorization.php';

// Your default office code.
$officeCode = "SomeOfficeCode";

$provider = new \PhpTwinfield\Secure\Provider\OAuthProvider([
    'clientId'                => $twin_client_id,
    'clientSecret'            => $twin_client_secret,
    'redirectUri'             => $twin_redirect_uri,
]);

// Retrieve a stored refresh token from storage.
$refreshTokenStorage = RetrieveRefreshTokenFromStore();

// Optionally retrieve access token, access token expiry time and cluster from storage.
$accessTokenStorage = RetrieveAccessTokenFromStore();

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

// Create a new connection using either the refresh token, access token and access cluster (approximately 2 seconds faster) or only the refresh token.
if ($accessTokenStorage['access_expiry'] > time()) {
    $connection  = new \PhpTwinfield\Secure\OpenIdConnectAuthentication($provider, $refreshTokenStorage['refresh_token'], $office, $accessTokenStorage['access_token'], $accessTokenStorage['access_cluster']);
} else {
    $connection  = new \PhpTwinfield\Secure\OpenIdConnectAuthentication($provider, $refreshTokenStorage['refresh_token'], $office);
}
