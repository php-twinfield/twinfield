<?php

/* Store a valid access token and cluster through a scheduled task/cron job running in the background.
 * Running the following code every 60 minutes (or a bit less as Access Tokens are valid for exactly 60 minutes)
 * will reduce connection time when working with the API (by about 2 seconds).
 * It will also reduce connection load on Twinfield when making more than 20-30 connections/day.

 * There are several methods to run a script every 45-50 minutes.
 * The method included in this script assumes the scheduled task/cron job is run every 10 minutes and uses the modified timestamp of a file
 * to determine last run time and only complete the entire script when the previous run time is more than 45 minutes ago.
 * By this logic the access token will be renewed every 50 minutes, with another try at exactly 60 minutes in case of a failure at 50 minutes
 */

function RetrieveRefreshTokenFromStore() {
    // Retrieve refresh token from some kind of storage (e.g. SQL database).

    $refreshTokenStorage                       = array();
    $refreshTokenStorage['refresh_token']      = 'SavedRefreshToken';

    return $refreshTokenStorage;
}

function SaveAccessTokenToStore(array $accessTokenStorage) {
    /* Save access token, expiry time and cluster to some kind of storage (e.g. SQL database).
     * $accessTokenStorage['access_token']
     * $accessTokenStorage['access_expiry']
     * $accessTokenStorage['access_cluster']
     */
}

function LastRunThreeQuartersAgo () {
    if (!file_exists('renewaccesstoken.timestamp')) {
        return true;
    }

    if (filemtime('renewaccesstoken.timestamp') > (time() - 60 * 45)) {
        return true;
    }

    return false;
}

// Only allow the script to run from the command line interface e.g. Cron
if (php_sapi_name() !='cli') exit;

// Only allow the script to run if last successful run time is more than 45 minutes ago
if (!LastRunThreeQuartersAgo()) exit;

require_once('vendor/autoload.php');

// The client ID assigned to you by Twinfield.
$twin_client_id = 'SomeClientId';

// The client secret assigned to you by Twinfield.
$twin_client_secret = 'SomeClientSecret';

// The FQDN URI of the script used for initial authorization, must be filled out on the form (Redirect URL) when requesting the client ID/Secret from Twinfield.
$twin_redirect_uri = 'https://example.org/twinfield/Authorization.php';

// Retrieve a stored Refresh token from storage.
$refreshTokenStorage = retrieveRefreshTokenFromStore();

$provider = new \PhpTwinfield\Secure\Provider\OAuthProvider([
    'clientId'                => $twin_client_id,
    'clientSecret'            => $twin_client_secret,
    'redirectUri'             => $twin_redirect_uri,
]);

// Get a new access token using the stored refresh token
$accessToken = $provider->getAccessToken('refresh_token', [
    'refresh_token' => $refreshTokenStorage['refresh_token']
]);

// Validate the access token and retrieve the access cluster
$validationUrl    = "https://login.twinfield.com/auth/authentication/connect/accesstokenvalidation?token=";
$validationResult = @file_get_contents($validationUrl . urlencode($accessToken->getToken()));

if ($validationResult !== false) {
    $resultDecoded                    = \json_decode($validationResult, true);

    $tokenStorage                     = array();
    $tokenStorage['access_token']     = $accessToken->getToken();
    $tokenStorage['access_expiry']    = $accessToken->getExpires();
    $tokenStorage['access_cluster']   = $resultDecoded["twf.clusterUrl"];

    // Save the new access token, expiry time and cluster to storage
    SaveAccessTokenToStore($tokenStorage);

    // Update the last renewal successful run time to right now
    touch('renewaccesstoken.timestamp');
}