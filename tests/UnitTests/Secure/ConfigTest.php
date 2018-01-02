<?php
namespace PhpTwinfield\Secure;

/**
 * @covers \PhpTwinfield\Secure\Config
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Config
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Config;
    }

    public function testGetUsername()
    {
        $this->object->setCredentials('testUsername', 'testPassword', 'testOrganization');
        $this->assertSame('testUsername', $this->object->getUsername());
        $this->assertSame('testPassword', $this->object->getPassword());
        $this->assertSame('testOrganization', $this->object->getOrganisation());
    }

    public function testSetOAuthParameters()
    {
        $this->object->setOAuthParameters(
            'testClientToken',
            'testClientSecret',
            'testRedirectURL'
        );

        $this->assertSame($this->object->getClientToken(), 'testClientToken');
        $this->assertSame($this->object->getClientSecret(), 'testClientSecret');
        $this->assertSame($this->object->getRedirectURL(), 'testRedirectURL');
    }
}
