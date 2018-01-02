<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Secure\Connection;
use PhpTwinfield\Services\BaseService;

abstract class BaseApiConnector
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * The service that is needed by this connector.
     *
     * @return Services
     */
    abstract protected function getRequiredWebservice(): Services;

    /**
     * @param AuthenticatedConnection $connection
     *
     * @throws Exception
     */
    public function __construct(AuthenticatedConnection $connection)
    {
        $this->service = $connection->getAuthenticatedClient($this->getRequiredWebservice());
    }
}
