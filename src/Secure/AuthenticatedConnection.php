<?php
namespace PhpTwinfield\Secure;

use PhpTwinfield\Exception;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Services\BaseService;
use PhpTwinfield\Services\LoginService;
use Webmozart\Assert\Assert;

/**
 * A connection to Twinfield API.
 */
abstract class AuthenticatedConnection
{
    /**
     * Log the client in.
     *
     * @throws Exception
     */
    abstract protected function login(): void;

    /**
     * @return \SoapHeader|\SoapHeader[]|null
     */
    abstract protected function getSoapHeaders();

    /**
     * Get the cluster where subsequent requests should be sent.
     *
     * @return string
     */
    abstract protected function getCluster(): string;

    /**
     * @var BaseService[]
     */
    private $authenticatedClients = [];

    /**
     * Get an authenticated client for a specific service/
     *
     * @param Services $service
     * @throws Exception
     * @return BaseService
     */
    public function getAuthenticatedClient(Services $service): BaseService
    {
        $this->login();

        $key = $service->getKey();

        if (!array_key_exists($key, $this->authenticatedClients)) {

            $classname = $service->getValue();

            $this->authenticatedClients[$key] = new $classname(
                "{$this->getCluster()}{$service->getValue()}",
                // $this->config->getSoapClientOptions() +
                ["cluster" => $this->getCluster()]
            );

            $this->authenticatedClients[$key]->__setSoapHeaders($this->getSoapHeaders());
        }

        return $this->authenticatedClients[$key];
    }
}
