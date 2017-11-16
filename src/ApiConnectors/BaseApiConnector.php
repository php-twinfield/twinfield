<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Secure\Config;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\Service;
use PhpTwinfield\Response\Response;
use PhpTwinfield\SoapClient;

/**
 * All Factories used by all components extend this factory for common shared methods that help normalize the usage
 * between different components.
 *
 * @author Leon Rowland <leon@rowland.nl>
 */
abstract class BaseApiConnector
{
    /**
     * Holds the secure config class
     *
     * @var Config
     */
    private $config;

    /**
     * Holds the secure login class
     *
     * @var Login
     */
    private $login;

    /**
     * Holds the response from a request.
     *
     * @var Response
     */
    private $response;

    /**
     * Pass in the Secure\Config class and it will automatically make the Secure\Login for you.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->login = new Login($config);
    }

    /**
     * Returns the response that was last set.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    protected function getClient(string $wsdl): SoapClient
    {
        return $this->getLogin()->getClient('%s' . $wsdl);
    }

    /**
     * Should be called by the child classes. Will set the response document from an attempted SOAP request.
     *
     * @param Response $response
     */
    protected function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }

    protected function getLogin(): Login
    {
        return $this->login;
    }

    protected function createService(): Service
    {
        return new Service($this->getLogin());
    }
}
