<?php
namespace Pronamic\Twinfield\Secure;

/**
 * Twinfield OAuth Client.
 * 
 * @package Pronamic\Twinfield
 * @author Mischa Molhoek <mischamolhoek@gmail.com>
 * @copyright (c) 2015, Pronamic
 * @version 0.0.2
 */

class OAuth
{
    private $initiationURL = 'https://login.twinfield.com/oauth/initiate.aspx';
    private $authenticationURL = "https://login.twinfield.com/oauth/login.aspx?oauth_token=%s";
    private $finalizationURL = 'https://login.twinfield.com/oauth/finalize.aspx';
    private $sessionName = 'PronamicTwinfieldOauthSession';
    private $temp_token = '';
    private $needToRedirect = true;
    private $autoRedirect = false;
    private $session=array();
    private $options;

    private function loadSession()
    {
        if (isset($GLOBALS['_SESSION'][$this->sessionName])) {
            $this->session = @unserialize($GLOBALS['_SESSION'][$this->sessionName]);
        } else {
            $this->initSession();
        }
    }

    private function initSession()
    {
        $this->session = array(
            'temp_token_secret' => null,
            'accessToken' => null,
            'accessSecret' => null,
        );
    }
    private function saveSession()
    {

        $GLOBALS['_SESSION'][$this->sessionName] = serialize($this->session);
    }

    private function clearSession()
    {
        setcookie($this->sessionName, null, -1, '/');
        $this->initSession();
    }

    private function getTempTokens()
    {
        $header = array(
            'Authorization: OAuth realm="Twinfield"'.
            ', oauth_consumer_key="'.urlencode($this->options['clientToken']).'"'.
            ', oauth_signature_method="PLAINTEXT"'.
            ', oauth_timestamp=""'.
            ', oauth_nonce=""'.
            ', oauth_callback="'.urlencode($this->options['redirectURL']).'"'.
            ', oauth_signature="'.urlencode($this->options['clientSecret']).'"'
        );
        $response = $this->curl($this->initiationURL, $header);
        if (array_key_exists('oauth_token', $response)) {
            $this->temp_token = $response['oauth_token'];
        }
        if (array_key_exists('oauth_token_secret', $response)) {
            $this->session['temp_token_secret'] = $response['oauth_token_secret'];
        }
        $this->saveSession();
    }

    private function getFinalTokens()
    {
        $header = array(
            'Authorization: OAuth realm="Twinfield"'.
            ', oauth_consumer_key="'.urlencode($this->options['clientToken']).'"'.
            ', oauth_signature_method="PLAINTEXT"'.
            ', oauth_signature="'.urlencode($this->options['clientSecret']).
            '&'.urlencode($this->session['temp_token_secret']).'"'.
            ', oauth_token="'.urlencode($GLOBALS['_GET']['oauth_token']).'"'.
            ', oauth_verifier="'.urlencode($GLOBALS['_GET']['oauth_verifier']).'"'
        );
        $response = $this->curl($this->finalizationURL, $header);
        if (array_key_exists('oauth_token', $response)) {
            $this->session['accessToken'] = $response['oauth_token'];
        }
        if (array_key_exists('oauth_token_secret', $response)) {
            $this->session['accessSecret'] = $response['oauth_token_secret'];
        }
        $this->saveSession();
    }

    /**
     * Handles all curl interactions
     *
     * @access private
     * @param $url
     * @param $header
     * @return array
     */
    private function curl($url, $header)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        parse_str(curl_exec($curl), $response);
        curl_close($curl);
        return $response;
    }

    private function hasOption($name)
    {
        return is_array($this->options) && array_key_exists($name, $this->options) && $this->options[$name] != '';
    }

    public function getRedirectURL()
    {
        return sprintf($this->authenticationURL, $this->temp_token);
    }

    public function getRedirectScript()
    {
        if ($this->temp_token != '') {
            return sprintf(
                "<script language=\"Javascript\">window.top.location.href=\"%s\"</script>",
                $this->getRedirectURL()
            );
        } else {
            return 'sorry, temp_token not set';
        }
    }

    public function willAutoRedirect()
    {
        return $this->autoRedirect;
    }

    public function needToRedirect()
    {
        return $this->needToRedirect;
    }

    public function initialize()
    {
        if ($this->session['temp_token_secret'] && !$this->session['accessToken']) {
            $this->getFinalTokens();
            if (!$this->session['accessToken']) {
                //temp_token_secret is invalid, lets get new one
                $this->clearSession();
                $this->initialize();
            }
            $this->needToRedirect = false;
        } elseif (!$this->session['temp_token_secret']) {
            $this->getTempTokens();
            if ($this->autoRedirect) {
                print $this->getRedirectScript();
                exit;
            }
        }
    }

    public function getParameters()
    {
        return array(
            'clientToken' => $this->options['clientToken'],
            'clientSecret' => $this->options['clientSecret'],
            'accessToken' => $this->session['accessToken'],
            'accessSecret' => $this->session['accessSecret']
        );
    }

    public function __construct($options)
    {
        $this->options = $options;
        if (!function_exists('curl_version')) {
            trigger_error("curl not installed", E_USER_ERROR);
        }
        if (!$this->hasOption('clientToken')) {
            trigger_error("clientToken option missing", E_USER_ERROR);
        }
        if (!$this->hasOption('clientSecret')) {
            trigger_error("clientSecret option missing", E_USER_ERROR);
        }
        if (!$this->hasOption('redirectURL')) {
            trigger_error("redirectURL option missing", E_USER_ERROR);
        }
        if (array_key_exists('sessionName', $options)) {
            $this->sessionName = $options['sessionName'];
        }
        if (array_key_exists('clearSession', $options) && $options['clearSession'] == true) {
            $this->clearSession();
        } else {
            $this->loadSession();
        }
        if (array_key_exists('autoRedirect', $options) && $options['autoRedirect'] == true) {
            $this->autoRedirect = true;
        }
        $this->initialize();
    }
}
