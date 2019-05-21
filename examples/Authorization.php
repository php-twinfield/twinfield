<?php

/* To use OAuth 2 for logging in it is necessary that a user with access rights to Twinfield grants authorization.

 * On loading a page containing the following code the user will be redirected to the Twinfield Login page.
 * After successful login and optionally consent the user will be redirected back to the page at which point the Access Token and Refresh Token can be retrieved.

 * For more information, please refer to: https://github.com/thephpleague/oauth2-client#usage
 */

function SaveRefreshTokenToStore(array $refreshTokenStorage) {
    /* Save refresh token and expiry time to some kind of storage (e.g. SQL database).
     * $refreshTokenStorage['refresh_token']
     * $refreshTokenStorage['refresh_expiry']
     */
}

function SaveAccessTokenToStore(array $accessTokenStorage) {
    /* Save access token, expiry time and cluster to some kind of storage (e.g. SQL database).
     * $accessTokenStorage['access_token']
     * $accessTokenStorage['access_expiry']
     * $accessTokenStorage['access_cluster']
     */
}

require_once('vendor/autoload.php');

// The client ID assigned to you by Twinfield.
$twin_client_id = 'SomeClientId';

// The client secret assigned to you by Twinfield.
$twin_client_secret = 'SomeClientSecret';

/* The FQDN URI where this script can be called from when Twinfield redirects back to after the user logs in.
 * Must be filled out on the form (Redirect URL) when requesting the client ID/Secret from Twinfield.
 */
$twin_redirect_uri = 'https://example.org/twinfield/Authorization.php';

$provider = new \PhpTwinfield\Secure\Provider\OAuthProvider([
    'clientId'                => $twin_client_id,
    'clientSecret'            => $twin_client_secret,
    'redirectUri'             => $twin_redirect_uri,
]);

// If we don't have an authorization code then get one.
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

// Check given state against previously stored one to mitigate CSRF attack.
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

        /* Twinfield's refresh token is valid for 550 days.
         * Renewing the refresh token can be done by requesting the user to reload this page and logging into Twinfield before the refresh token is invalidated after 550 days.
         * NOTE: Remember to put in place functionality to request the user to renew their authorization, see RenewAuthorization.php for an example.
         */

        $refresh_expiry = strtotime(date('Ymd') . " +550 days");

        // Save refresh token and refresh token expiry time to storage.
        $refreshTokenStorage                      = array();
        $refreshTokenStorage['refresh_token']     = $accessToken->getRefreshToken();
        $refreshTokenStorage['refresh_expiry']    = $refresh_expiry;

        SaveRefreshTokenToStore($refreshTokenStorage);

        /* Optionally save access token, access token expiry time and access cluster to storage.
         * If you choose to use an always valid access token to login to speed up your requests you need to put in place functionality to automatically renew the access token using the save refresh token.
         * As the access token is valid for 60 minutes you need to create a task scheduler/cron that runs at least once every hour, see RenewAccessToken.php for an example.
         */

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