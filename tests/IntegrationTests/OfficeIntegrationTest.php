<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Enums\Services;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class OfficeIntegrationTest extends TestCase
{
    /**
     * @var OfficeApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $officeApiConnector;
    
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
            
        $this->officeApiConnector = new OfficeApiConnector($this->connection);
    }

    protected function getSuccessfulResponse(): Response
    {
        return Response::fromString('<dimension result="1" />');
    }

    public function testListOfficesWithoutCompanyId()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/officeOauthGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Catalog\Office::class))
            ->willReturn($response);

        $offices = $this->officeApiConnector->listAllWithoutOfficeCode();
        $this->assertCount(2, $offices);

        $this->assertSame('001', $offices[0]->getCode());
        $this->assertSame('MORE&Zo Services BV', $offices[0]->getName());

        $this->assertSame('010', $offices[1]->getCode());
        $this->assertSame('More&Zo Holding', $offices[1]->getName());
    }
}