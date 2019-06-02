<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\ApiConnectors\ArticleApiConnector;
use PhpTwinfield\Article;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

class ArticleApiConnectorTest extends TestCase
{
    /**
     * @var ArticleApiConnector
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

        $this->apiConnector = new ArticleApiConnector($connection);
    }

    private function createArticle(): Article
    {
        $article = new Article();
        return $article;
    }

    public function testSendAllReturnsMappedObjects()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__."/resources/article-response.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $article = $this->createArticle();

        $mapped = $this->apiConnector->send($article);

        $this->assertInstanceOf(Article::class, $mapped);
        $this->assertEquals("9060", $mapped->getCode());
        $this->assertEquals("Test Article", $mapped->getName());
    }
}
