<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\SoapClient;
use PhpTwinfield\Services\ProcessXmlService;
use PHPUnit\Framework\TestCase;

abstract class BaseIntegrationTest extends TestCase
{
    /**
     * @var Office
     */
    protected $office;

    /**
     * @var Login|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $login;

    /**
     * @var SoapClient|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->office = new Office();
        $this->office->setCode("OFFICE");

        $this->client = $this->createMock(ProcessXmlService::class);

        $this->login  = $this->createMock(Login::class);

        $this->login->expects($this->any())
            ->method("getAuthenticatedClient")
            ->with(Services::PROCESSXML())
            ->willReturn($this->client);
    }

    final protected function getSuccessfulResponse(): Response
    {
        $responseDocument = new \DOMDocument();
        $responseDocument->loadXML('<dimension result="1" />');
        return new Response($responseDocument);
    }
}