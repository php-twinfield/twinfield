<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Response\Response;

class OfficeIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var OfficeApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $officeApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->officeApiConnector = new OfficeApiConnector($this->connection);
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