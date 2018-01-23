<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Exception;
use PhpTwinfield\Secure\Connection;
use PhpTwinfield\Services\BaseService;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;

abstract class BaseApiConnector
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ProcessXmlService
     */
    private $processXmlService;

    /**
     * @var FinderService
     */
    private $finderService;

    /**
     * @param Connection $connection
     * @throws Exception
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function getProcessXmlService(): ProcessXmlService
    {
        if (!$this->processXmlService) {
            $this->processXmlService = $this->connection->getAuthenticatedClient(Services::PROCESSXML());
        }

        return $this->processXmlService;
    }

    protected function getFinderService(): FinderService
    {
        if (!$this->finderService) {
            $this->finderService = $this->connection->getAuthenticatedClient(Services::FINDER());
        }

        return $this->finderService;
    }
}