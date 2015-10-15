<?php
namespace Pronamic\Twinfield\Factory;

use \Pronamic\Twinfield\Secure\Config;
use \Pronamic\Twinfield\Secure\Login;
use \Pronamic\Twinfield\Secure\Service;
use \Pronamic\Twinfield\Response\Response;

/**
 * All Factories used by all components extend this factory for common
 * shared methods that help normalize the usage between different components.\
 * 
 * @note this is a facade pattern. Named factory now, cant change it.
 * 
 * @author Leon Rowland <leon@rowland.nl>
 */
abstract class ParentFactory
{
    /**
     * Holds the secure config class
     * 
     * @var \Pronamic\Twinfield\Secure\Config
     */
    private $config;

    /**
     * Holds the secure login class
     * 
     * @var \Pronamic\Twinfield\Secure\Login
     */
    private $login;

    /**
     * Holds the response from a request.
     * 
     * @var \Pronamic\Twinfield\Response\Response
     */
    private $response;

    /**
     * Pass in the Secure\Config class and it will automatically
     * make the Secure\Login for you.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Secure\Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
        $this->makeLogin();
    }

    /**
     * Sets the config class for usage in this factory
     * instance.
     * 
     * Returns the instance back.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Secure\Config $config
     * @return \Pronamic\Twinfield\Factory\ParentFactory
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

	/**
	 * @param string $wsdl the resource location
	 * @return \SoapClient
	 */
	public function getClient($wsdl)
	{
		return $this->getLogin()->getClient('%s'.$wsdl);
	}

    /**
     * Returns this instances Secure\Config instance.
     * 
     * @access public
     * @return \Pronamic\Twinfield\Secure\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Makes an instance of Secure\Login with the passed in 
     * Secure\Config instance.
     * 
     * @access public
     * @return boolean
     */
    public function makeLogin()
    {
        return $this->login = new Login($this->getConfig());
    }

    /**
     * Returns this instances associated login instance.
     * 
     * @access public
     * @return \Pronamic\Twinfield\Secure\Login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns an new instance of Service with 
     * the already prepared Secure\Login.
     * 
     * @access public
     * @return \Pronamic\Twinfield\Secure\Service
     */
    public function getService()
    {
        return new Service($this->getLogin());
    }

    /**
     * Should be called by the child classes. Will set the response
     * document from an attempted SOAP request.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Response\Response $response
     * @return \Pronamic\Twinfield\Factory\ParentFactory
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Returns the response that was last set.
     * 
     * @access public
     * @return \Pronamic\Twinfield\Response\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
