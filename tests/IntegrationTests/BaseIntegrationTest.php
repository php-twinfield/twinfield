<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

abstract class BaseIntegrationTest extends TestCase
{
    /**
     * @var Office
     */
    protected $office;

    /**
     * @var AuthenticatedConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $connection;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

    /**
     * @var FinderService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $finderService;

    protected function setUp()
    {
        parent::setUp();

        $this->office = new Office();
        $this->office->setCode("11024");

        $this->processXmlService = $this->createPartialMock(ProcessXmlService::class, ['sendDocument']);
        $this->finderService     = $this->createPartialMock(FinderService::class, ['searchFinder']);

        $this->connection  = $this->createMock(AuthenticatedConnection::class);
        $this->connection->expects($this->any())
            ->method("getAuthenticatedClient")
            ->willReturnCallback(function (Services $service) {
                switch ($service->getValue()) {
                    case Services::PROCESSXML()->getValue():
                        return $this->processXmlService;

                    case Services::FINDER()->getValue():
                        return $this->finderService;
                }

                throw new \InvalidArgumentException("Unknown service {$service->getValue()}");
            });
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString('<dimension result="1" />');
    }
}