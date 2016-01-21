<?php
namespace Pronamic\Twinfield\Secure;

use Pronamic\Twinfield\Customer\Customer;
use Pronamic\Twinfield\Customer\DOM\CustomersDocument;

class CustomerOfficeTest extends \PHPUnit_Framework_TestCase
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
        $this->office = 'TEST-001';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Pronamic\Twinfield\Customer\Customer::setOffice
     * @todo   Implement testSetOffice().
     */
    public function testSetOffice()
    {
        $customer = new Customer;
        
        $customer->setOffice($this->office);
        
        $this->assertEquals($this->office, $customer->getOffice());
        
        return $customer;
        
    }
    
    /**
     * Checks if the office field is correctly serialized
     * 
     * @depends testSetOffice
     */
    public function testSetOfficeAndSerializes(Customer $customer)
    {
        $document = new CustomersDocument;
        
        $document->addCustomer($customer);
        
        $xpath = new \DOMXPath($document);
        
        $this->assertEquals($xpath->query('/dimension/office')->item(0)->nodeValue, $this->office );
    }
}
