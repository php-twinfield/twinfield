<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\CustomerApiConnector;
use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\CustomerAddress;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Secure\Login;
use PhpTwinfield\Secure\Service;

/**
 * @covers Customer
 * @covers CustomerAddress
 * @covers CustomerBank
 * @covers CustomerCreditManagement
 * @covers CustomersDocument
 * @covers CustomerMapper
 * @covers CustomerApiConnector
 */
class CustomerIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Login|\PHPUnit_Framework_MockObject_MockObject
     */
    private $login;

    /**
     * @var Service|\PHPUnit_Framework_MockObject_MockObject
     */
    private $service;

    /**
     * @var CustomerApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->login   = $this->createMock(Login::class);
        $this->service = $this->createMock(Service::class);

        $this->login
            ->expects($this->any())
            ->method('process')
            ->willReturn(true);

        $this->customerApiConnector = $this->createPartialMock(
            CustomerApiConnector::class,
            ['getLogin', 'createService']
        );

        $this->customerApiConnector
            ->expects($this->any())
            ->method('createService')
            ->willReturn($this->service);

        $this->customerApiConnector
            ->expects($this->any())
            ->method('getLogin')
            ->willReturn($this->login);
    }

    public function testGetCustomerWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/customerGetResponse.xml')));
        $response = new Response($domDocument);

        $this->service
            ->expects($this->any())
            ->method('send')
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Customer::class))
            ->willReturn($response);

        $customer = $this->customerApiConnector->get('CODE', 'OFFICE');

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame('001', $customer->getOffice());
        $this->assertSame('DEB', $customer->getType());
        $this->assertSame('Customer 0', $customer->getName());
        $this->assertSame('http://www.example.com', $customer->getWebsite());

        // Financials
        $this->assertSame('30', $customer->getDueDays());
        $this->assertSame('true', $customer->getPayAvailable());
        $this->assertSame('SEPANLDD', $customer->getPayCode());
        $this->assertSame('false', $customer->getEBilling());
        $this->assertSame('VN', $customer->getVatCode());

        // Addresses
        $addresses = $customer->getAddresses();
        $this->assertCount(1, $addresses);
        $this->assertArrayHasKey('1', $addresses);

        /** @var CustomerAddress $address */
        $address = $addresses['1'];

        $this->assertSame('1', $address->getID());
        $this->assertSame('invoice', $address->getType());
        $this->assertSame('true', $address->getDefault());
        $this->assertSame('Customer 0', $address->getName());
        $this->assertSame('NL', $address->getCountry());
        $this->assertSame('Place', $address->getCity());
        $this->assertSame('1000', $address->getPostcode());
        $this->assertSame('010-123452000', $address->getTelephone());
        $this->assertSame('010-12342000', $address->getFax());
        $this->assertSame('info@example.com', $address->getEmail());
        $this->assertSame('', $address->getContact());
        $this->assertSame('Customer 1', $address->getField1());
        $this->assertSame('Streetname part 1 - 1', $address->getField2());
        $this->assertSame('Streetname part 1 - 2', $address->getField3());
        $this->assertSame('NL099887766B01', $address->getField4());
        $this->assertSame('99887766', $address->getField5());
        $this->assertSame('', $address->getField6());

        // Banks
        $banks = $customer->getBanks();
        $this->assertCount(1, $banks);
        $this->assertArrayHasKey('-1', $banks);

        /** @var CustomerBank $bank */
        $bank = $banks['-1'];

        $this->assertSame('-1', $bank->getID());
        $this->assertSame('true', $bank->getDefault());
        $this->assertSame('Customer 1', $bank->getAscription());
        $this->assertSame('123456789', $bank->getAccountnumber());
        $this->assertSame('ABN Amro', $bank->getBankname());
        $this->assertSame('ABNANL2A', $bank->getBiccode());
        $this->assertSame('Place', $bank->getCity());
        $this->assertSame('NL', $bank->getCountry());
        $this->assertSame('NL02ABNA0123456789', $bank->getIban());
        $this->assertSame('', $bank->getNatbiccode());
        $this->assertSame('', $bank->getPostcode());
        $this->assertSame('', $bank->getState());
        $this->assertNull($bank->getAddressField2());
        $this->assertNull($bank->getAddressField3());

        $this->assertSame('1097', $customer->getCode());
        $this->assertSame('c5027760-476e-4081-85fb-351c983aea54', $customer->getUID());
        $this->assertSame('false', $customer->getInUse());
        $this->assertSame('normal', $customer->getBehaviour());
        $this->assertSame('0', $customer->getTouched());
        $this->assertSame('0', $customer->getBeginPeriod());
        $this->assertSame('0', $customer->getBeginYear());
        $this->assertSame('0', $customer->getEndPeriod());
        $this->assertSame('0', $customer->getEndYear());
        $this->assertSame('true', $customer->getEditDimensionName());

        // Creditmanagement
        $creditmanagement = $customer->getCreditManagement();

        $this->assertSame('', $creditmanagement->getResponsibleUser());
        $this->assertSame('0.00', $creditmanagement->getBaseCreditLimit());
        $this->assertSame('true', $creditmanagement->getSendReminder());
        $this->assertSame('', $creditmanagement->getReminderEmail());
        $this->assertSame('false', $creditmanagement->getBlocked());
        $this->assertSame('', $creditmanagement->getFreeText1());
        $this->assertSame('', $creditmanagement->getFreeText2());
        $this->assertSame('', $creditmanagement->getFreeText3());
        $this->assertSame('', $creditmanagement->getComment());
    }

    public function testListAllCustomersWorks()
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(realpath(__DIR__ . '/resources/customerListResponse.xml')));
        $response = new Response($domDocument);

        $this->service
            ->expects($this->any())
            ->method('send')
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Catalog\Dimension::class))
            ->willReturn($response);

        $customers = $this->customerApiConnector->listAll('OFFICE');

        $this->assertCount(3, $customers);

        $this->assertArrayHasKey('D1000', $customers);
        $this->assertSame('John Doe', $customers['D1000']['name']);

        $this->assertArrayHasKey('D1001', $customers);
        $this->assertSame('B. Terwel', $customers['D1001']['name']);

        $this->assertArrayHasKey('D1002', $customers);
        $this->assertSame('Hr E G H Küppers en/of MW M.J. Küppers-Veeneman', $customers['D1002']['name']);
    }

    public function testSendCustomerWorks()
    {
        $customer = new Customer();
        $customer->setOffice('001');
        $customer->setType('DEB');
        $customer->setName('Customer 0');
        $customer->setDueDays('30');
        $customer->setPayAvailable('true');
        $customer->setPayCode('SEPANLDD');

        $address = new CustomerAddress();
        $address->setID('1');
        $address->setType('invoice');
        $address->setDefault('true');
        $address->setName('Customer 0');
        $address->setCountry('NL');
        $address->setCity('Place');
        $address->setPostcode('1000');
        $address->setTelephone('010-123452000');
        $address->setFax('010-12342000');
        $address->setEmail('info@example.com');
        $address->setField1('Customer 1');
        $address->setField2('Streetname part 1 - 1');
        $address->setField3('Streetname part 1 - 2');
        $customer->addAddress($address);

        $bank = new CustomerBank();
        $bank->setDefault('true');
        $bank->setAscription('Customer 1');
        $bank->setAccountnumber('123456789');
        $bank->setAddressField2('');
        $bank->setAddressField3('');
        $bank->setBankname('ABN Amro');
        $bank->setBiccode('ABNANL2A');
        $bank->setCity('Place');
        $bank->setCountry('NL');
        $bank->setIban('NL02ABNA0123456789');
        $bank->setNatbiccode('');
        $bank->setPostcode('');
        $bank->setState('');
        $customer->addBank($bank);

        $this->service
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(CustomersDocument::class))
            ->willReturnCallback(function (CustomersDocument $customersDocument) {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(realpath(__DIR__ . '/resources/customerSendRequest.xml')),
                    $customersDocument->saveXML()
                );

                return new Response($customersDocument);
            });

        $this->customerApiConnector->send($customer);
    }
}
