<?php
namespace PhpTwinfield\Secure;

/**
 * Config class
 *
 * Used to store the config information used to
 * generate a secure soap client and use the rest
 * of the application.
 *
 * There is no constructor.  You set all the values
 * with the method setCredentials();
 *
 * @since 0.0.1
 *
 * @package PhpTwinfield
 * @subpackage Secure
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Leon Rowland
 * @version 0.0.1
 */
final class Config
{
    /**
     * @var string|null
     */
    private $username;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string
     */
    private $organization;

    /**
     * Holds all the OAuth login details for this
     * config object
     *
     * @access private
     * @var array
     */
    private $oauthCredentials = array(
        'clientToken'     => '',
        'clientSecret' => ''
    );


    /**
     * Holds all the OAuth class
     *
     * @access private
     */
    private $oauth = null;


    /**
     * Holds the optional soap client options
     *
     * @var array
     */
    private $soapClientOptions = array();

    /**
     * Sets the oAuth details for this config object.
     *
     * @access public
     * @param string $ct
     * @param string $cs
     * @param string $rURL
     * @param bool $autoRedirect
     * @param bool $clearSession
     * @return void
     */
    public function setOAuthParameters($ct, $cs, $rURL, bool $autoRedirect = false, bool $clearSession = false): void
    {
        $this->oauthCredentials['clientToken'] = $ct;
        $this->oauthCredentials['clientSecret'] = $cs;
        $this->oauthCredentials['redirectURL'] = $rURL;
        $this->oauthCredentials['autoRedirect'] = $autoRedirect;
        $this->oauthCredentials['clearSession'] = $clearSession;
    }

    /**
     * Gets the oAuth parameters of this config
     * object. It will create a new OAuth class
     * which will cause a redirect to twinfield
     * when $autoRedirect was set to true in
     * setOAuthParameters
     *
     * @since 0.0.1
     *
     * @access public
     * @return array
     */
    public function getOAuthParameters()
    {
        $this->oauth = new OAuth($this->oauthCredentials);
        return $this->oauth->getParameters();
    }

    /**
     * Sets the details for this config
     * object.
     *
     * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Authentication/WebServices
     * @param string $username
     * @param string $password
     * @param string $organisation
     */
    public function setCredentials(string $username, string $password, string $organisation)
    {
        $this->username = $username;
        $this->password = $password;
        $this->organization = $organisation;
    }

    /**
     * Returns the entire collection of details
     *
     * @since 0.0.1
     *
     * @access public
     * @return array
     */
    public function getCredentials(): array
    {
        if ($this->oauthCredentials['clientToken'] != '') {
            return $this->getOAuthParameters();
        }

        return [
            "username" => $this->getUsername(),
            "password" => $this->getPassword(),
        ];
    }

    /**
     * Returns the set username
     */
    final public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Returns the set password
     */
    final public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Returns the set organisation code
     */
    final public function getOrganisation(): string
    {
        return $this->organization;
    }

    /**
     * Returns the set clientToken
     *
     * @since 0.0.2
     *
     * @access public
     * @return string
     */
    public function getClientToken()
    {
        return $this->oauthCredentials['clientToken'];
    }

    /**
     * Returns the set clientSecret
     *
     * @since 0.0.2
     *
     * @access public
     * @return string
     */
    public function getClientSecret()
    {
        return $this->oauthCredentials['clientSecret'];
    }

    /**
     * Returns the set redirectURL
     *
     * @since 0.0.2
     *
     * @access public
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->oauthCredentials['redirectURL'];
    }

    public function getSoapClientOptions()
    {
        return $this->soapClientOptions;
    }

    /**
     * Set options that will be passed to the SoapClient constructor.
     *
     * Use this for setting a stream context or a proxy.
     *
     * @param array $options
     * @return $this
     */
    public function setSoapClientOptions(array $options)
    {
        $this->soapClientOptions = $options;
        return $this;
    }
}
