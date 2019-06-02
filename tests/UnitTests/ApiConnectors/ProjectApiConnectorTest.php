<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\ProjectApiConnector;
use PhpTwinfield\Project;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class ProjectApiConnectorTest extends TestCase
{
    /**
     * @var ProjectApiConnector
     */
    protected $apiConnector;

    /**
     * @var ProcessXmlService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processXmlService;

    protected function setUp()
    {
        parent::setUp();

        $this->processXmlService = $this->getMockBuilder(ProcessXmlService::class)
            ->setMethods(["sendDocument"])
            ->disableOriginalConstructor()
            ->getMock();

        /** @var AuthenticatedConnection|\PHPUnit_Framework_MockObject_MockObject $connection */
        $connection = $this->createMock(AuthenticatedConnection::class);
        $connection
            ->expects($this->any())
            ->method("getAuthenticatedClient")
            ->willReturn($this->processXmlService);

        $this->apiConnector = new ProjectApiConnector($connection);
    }

    private function createProject(): Project
    {
        $project = new Project();
        return $project;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/project-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $project = $this->createProject();

        $mapped = $this->apiConnector->send($project);

        $this->assertInstanceOf(Project::class, $mapped);
        $this->assertEquals("P0000", $mapped->getCode());
        $this->assertEquals("Project direct", $mapped->getName());
    }
}
