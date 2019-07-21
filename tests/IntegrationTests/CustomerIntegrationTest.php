<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\CustomerApiConnector;
use PhpTwinfield\Currency;
use PhpTwinfield\Customer;
use PhpTwinfield\CustomerAddress;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\CustomerCollectMandate;
use PhpTwinfield\CustomerFinancials;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Util;

/**
 * @covers Customer
 * @covers CustomerAddress
 * @covers CustomerBank
 * @covers CustomerCollectMandate
 * @covers CustomerCreditManagement
 * @covers CustomerFinancials
 * @covers CustomersDocument
 * @covers CustomerMapper
 * @covers CustomerApiConnector
 */
class CustomerIntegrationTest extends BaseIntegrationTest
{

    /**
     * @var CustomerApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customerApiConnector;

    protected function setUp()
    {
        parent::setUp();

        $this->customerApiConnector = new CustomerApiConnector($this->connection);
    }

    public function testGetCustomerWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/customerGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Customer::class))
            ->willReturn($response);

        $customer = $this->customerApiConnector->get('CODE', Office::fromCode('001'));

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame('001', Util::objectToStr($customer->getOffice()));
        $this->assertSame('DEB', Util::objectToStr($customer->getType()));
        $this->assertSame('Customer 0', $customer->getName());
        $this->assertSame('http://www.example.com', $customer->getWebsite());

        // Financials
        $financials = $customer->getFinancials();
        $this->assertSame(30, $financials->getDueDays());
        $this->assertSame(true, $financials->getPayAvailable());
        $this->assertSame('SEPANLDD', Util::objectToStr($financials->getPayCode()));
        $this->assertSame(false, $financials->getEBilling());
        $this->assertSame('VN', Util::objectToStr($financials->getVatCode()));

        // Collect Mandate
        $collectMandate = $financials->getCollectMandate();
        $this->assertSame('1', $collectMandate->getID());
        $this->assertEquals(new \DateTimeImmutable('2018-06-04'), $collectMandate->getSignatureDate());
        $this->assertEquals(new \DateTimeImmutable('2018-06-08'), $collectMandate->getFirstRunDate());

        // Addresses
        $addresses = $customer->getAddresses();
        $this->assertCount(1, $addresses);
        $this->assertArrayHasKey(0, $addresses);

        /** @var CustomerAddress $address */
        $address = $addresses[0];

        $this->assertSame(1, $address->getID());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\AddressType');
        $this->assertSame($ReflectObject->getConstant('INVOICE'), (string)$address->getType());
        $this->assertSame(true, $address->getDefault());
        $this->assertSame('Customer 0', $address->getName());
        $this->assertSame('NL', Util::objectToStr($address->getCountry()));
        $this->assertSame('Place', $address->getCity());
        $this->assertSame('1000', $address->getPostcode());
        $this->assertSame('010-123452000', $address->getTelephone());
        $this->assertSame('010-12342000', $address->getTelefax());
        $this->assertSame('info@example.com', $address->getEmail());
        $this->assertSame('Customer 1', $address->getField1());
        $this->assertSame('Streetname part 1 - 1', $address->getField2());
        $this->assertSame('Streetname part 1 - 2', $address->getField3());
        $this->assertSame('NL099887766B01', $address->getField4());
        $this->assertSame('99887766', $address->getField5());
        $this->assertNull($address->getField6());

        // Banks
        $banks = $customer->getBanks();
        $this->assertCount(1, $banks);
        $this->assertArrayHasKey(0, $banks);

        /** @var CustomerBank $bank */
        $bank = $banks[0];

        $this->assertSame(-1, $bank->getID());
        $this->assertSame(true, $bank->getDefault());
        $this->assertSame('Customer 1', $bank->getAscription());
        $this->assertSame('123456789', $bank->getAccountNumber());
        $this->assertSame('ABN Amro', $bank->getBankName());
        $this->assertSame('ABNANL2A', $bank->getBicCode());
        $this->assertSame('Place', $bank->getCity());
        $this->assertSame('NL', Util::objectToStr($bank->getCountry()));
        $this->assertSame('NL02ABNA0123456789', $bank->getIban());
        $this->assertNull($bank->getNatBicCode());
        $this->assertNull($bank->getPostcode());
        $this->assertNull($bank->getState());
        $this->assertNull($bank->getAddressField2());
        $this->assertNull($bank->getAddressField3());

        $this->assertSame('1097', $customer->getCode());
        $this->assertSame('c5027760-476e-4081-85fb-351c983aea54', $customer->getUID());
        $this->assertSame(false, $customer->getInUse());
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\Behaviour');
        $this->assertSame($ReflectObject->getConstant('NORMAL'), (string)$customer->getBehaviour());
        $this->assertSame(0, $customer->getTouched());
        $this->assertSame(0, $customer->getBeginPeriod());
        $this->assertSame(0, $customer->getBeginYear());
        $this->assertSame(0, $customer->getEndPeriod());
        $this->assertSame(0, $customer->getEndYear());

        // Creditmanagement
        $creditmanagement = $customer->getCreditManagement();

        $this->assertNull(Util::objectToStr($creditmanagement->getResponsibleUser()));
        $this->assertSame('0.00', Util::formatMoney($creditmanagement->getBaseCreditLimit()));
        $ReflectObject = new \ReflectionClass('\PhpTwinfield\Enums\SendReminder');
        $this->assertSame($ReflectObject->getConstant('TRUE'), (string)$creditmanagement->getSendReminder());
        $this->assertNull($creditmanagement->getReminderEmail());
        $this->assertSame(false, $creditmanagement->getBlocked());
        $this->assertNull($creditmanagement->getFreeText1());
        $this->assertNull($creditmanagement->getFreeText2());
        $this->assertNull($creditmanagement->getFreeText3());
        $this->assertNull($creditmanagement->getComment());
    }

    public function testListAllCustomersWorks()
    {
        $response = (object)json_decode(file_get_contents(__DIR__ . '/resources/customerListResponse.json'));

        $this->finderService
            ->expects($this->once())
            ->method("searchFinder")
            ->willReturn($response);

        $customers = $this->customerApiConnector->listAll();

        $this->assertCount(3, $customers);

        $this->assertSame('D1000', $customers[0]->getCode());
        $this->assertSame('John Doe', $customers[0]->getName());

        $this->assertSame('D1001', $customers[1]->getCode());
        $this->assertSame('B. Terwel', $customers[1]->getName());

        $this->assertSame('D1002', $customers[2]->getCode());
        $this->assertSame('Hr E G H Küppers en/of MW M.J. Küppers-Veeneman', $customers[2]->getName());
    }

    public function testSendCustomerWorks()
    {
        $customer = new Customer();
        $customer->setOffice(Office::fromCode('001'));
        $customer->setName('Customer 0');

        $financials = new CustomerFinancials();
        $financials->setDueDays(30);
        $financials->setPayAvailable(true);
        $financials->setPayCode(\PhpTwinfield\PayCode::fromCode('SEPANLDD'));

        $collectMandate = new CustomerCollectMandate();
        $collectMandate->setID(1);
        $collectMandate->setSignatureDate(Util::parseDate('20180604'));
        $collectMandate->setFirstRunDate(Util::parseDate('20180608'));
        $financials->setCollectMandate($collectMandate);

        $customer->setFinancials($financials);

        $address = new CustomerAddress();
        $address->setID(1);
        $address->setType(\PhpTwinfield\Enums\AddressType::INVOICE());
        $address->setDefault(true);
        $address->setName('Customer 0');
        $address->setCountry(\PhpTwinfield\Country::fromCode('NL'));
        $address->setCity('Place');
        $address->setPostcode('1000');
        $address->setTelephone('010-123452000');
        $address->setTelefax('010-12342000');
        $address->setEmail('info@example.com');
        $address->setField1('Customer 1');
        $address->setField2('Streetname part 1 - 1');
        $address->setField3('Streetname part 1 - 2');
        $customer->addAddress($address);

        $bank = new CustomerBank();
        $bank->setDefault(true);
        $bank->setAscription('Customer 1');
        $bank->setAccountNumber('123456789');
        $bank->setAddressField2('');
        $bank->setAddressField3('');
        $bank->setBankName('ABN Amro');
        $bank->setBicCode('ABNANL2A');
        $bank->setCity('Place');
        $bank->setCountry(\PhpTwinfield\Country::fromCode('NL'));
        $bank->setIban('NL02ABNA0123456789');
        $bank->setNatBicCode('');
        $bank->setPostcode('');
        $bank->setState('');
        $customer->addBank($bank);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(CustomersDocument::class))
            ->willReturnCallback(function (CustomersDocument $customersDocument): Response {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(__DIR__ . '/resources/customerSendRequest.xml'),
                    $customersDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->customerApiConnector->send($customer);
    }
}
