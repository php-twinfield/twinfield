<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

/* A user with login access to Twinfield needs to grant authorization to the API at least every 550 days.
 * Consider running this script through a task schedular/Cron once every week to check if the authorization needs to be imminently renewed.
 * Email the user the request for renewal of the authorization at that time and keep resending the request every week until authorization is granted.
 */

function RetrieveRefreshTokenFromStore() {
    // Retrieve refresh token expiry time from some kind of storage (e.g. SQL database).

    $refreshTokenStorage                       = array();
    $refreshTokenStorage['refresh_expiry']     = 'SavedRefreshExpiryTimeStamp';

    return $refreshTokenStorage;
}

function SendEmail(string $toEmail, string $fromEmail, string $subject, string $body) {
    // Function that sends an email to $toEmail, from $fromEmail with subject $subject and body $body
}

// Only allow the script to run from the command line interface e.g. Cron
if (php_sapi_name() !='cli') exit;

// The FQDN URI of the script used for initial authorization, must be filled out on the form (Redirect URL) when requesting the client ID/Secret from Twinfield.
$twin_redirect_uri = 'https://example.org/twinfield/Authorization.php';

// Amount of days before the refresh token expires after 550 days to start asking the user for renewal
$daysLeftAfterWhichRequestRenewal = 60;

// Email of user that grants access to the API to communicate with Twinfield
$accountingAdminEmail = 'accounting@example.com';

// From email
$fromEmail = 'noreply@example.com';

// Subject of email requesting renewal of the authorization
$subject = 'Renew Twinfield domain/application authorization';

// Body of email requesting renewal of the authorization
$body = "Dear Sir/Madam,<br /><br />

The Twinfield authorization for domain/application is about to expire.<br />
Please renew the authorization as soon as possible.<br />
Renew the authorization by clicking <a href=\"{$twin_redirect_uri}\">here</a> and logging in with Twinfield.<br /><br />

You will receive this email once a week until the authorization is renewed.";

if ($refreshTokenStorage['refresh_expiry'] < (time() + ($daysLeftAfterWhichRequestRenewal * 60 * 60 * 24))) {
    SendEmail($accountingAdminEmail, $fromEmail, $subject, $body);
}
?>
