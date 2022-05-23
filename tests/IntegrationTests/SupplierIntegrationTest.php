<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\SupplierApiConnector;
use PhpTwinfield\Supplier;
use PhpTwinfield\SupplierAddress;
use PhpTwinfield\SupplierBank;
use PhpTwinfield\DomDocuments\SuppliersDocument;
use PhpTwinfield\Mappers\SupplierMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Response\Response;

/**
 * @covers Supplier
 * @covers SupplierAddress
 * @covers SupplierBank
 * @covers SuppliersDocument
 * @covers SupplierMapper
 * @covers SupplierApiConnector
 */
class SupplierIntegrationTest extends BaseIntegrationTest
{
    /**
     * @var SupplierApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $supplierApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplierApiConnector = new SupplierApiConnector($this->connection);
    }

    public function testGetSupplierWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/supplierGetResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Read\Supplier::class))
            ->willReturn($response);

        $supplier = $this->supplierApiConnector->get('CODE', $this->office);

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertSame('001', $supplier->getOffice()->getCode());
        $this->assertSame('CRD', $supplier->getType());
        $this->assertSame('Supplier 0', $supplier->getName());
        $this->assertSame('http://www.example.com', $supplier->getWebsite());

        // Financials
        $this->assertSame('1', $supplier->getDueDays());
        $this->assertSame(true, $supplier->getPayAvailable());
        $this->assertSame('SEPANLCT', $supplier->getPayCode());
        $this->assertSame(false, $supplier->getEBilling());

        // Addresses
        $addresses = $supplier->getAddresses();
        $this->assertCount(1, $addresses);
        $this->assertArrayHasKey('1', $addresses);

        /** @var SupplierAddress $address */
        $address = $addresses['1'];

        $this->assertSame('1', $address->getID());
        $this->assertSame('invoice', $address->getType());
        $this->assertSame('true', $address->getDefault());
        $this->assertSame('Supplier 0', $address->getName());
        $this->assertSame('NL', $address->getCountry());
        $this->assertSame('Place 2000', $address->getCity());
        $this->assertSame('2000', $address->getPostcode());
        $this->assertSame('010-123452000', $address->getTelephone());
        $this->assertSame('010-12342000', $address->getFax());
        $this->assertSame('info@example.com', $address->getEmail());
        $this->assertSame('', $address->getContact());
        $this->assertSame('supplier 2000', $address->getField1());
        $this->assertSame('Streetname part 1 - 2000', $address->getField2());
        $this->assertSame('Streetname part 2000', $address->getField3());
        $this->assertSame('155676842B01', $address->getField4());
        $this->assertSame('', $address->getField5());
        $this->assertSame('', $address->getField6());

        // Banks
        $banks = $supplier->getBanks();
        $this->assertCount(1, $banks);
        $this->assertArrayHasKey('1', $banks);

        /** @var SupplierBank $bank */
        $bank = $banks['1'];

        $this->assertSame('1', $bank->getID());
        $this->assertSame('true', $bank->getDefault());
        $this->assertSame('Supplier 2000', $bank->getAscription());
        $this->assertSame('123456789', $bank->getAccountnumber());
        $this->assertSame('', $bank->getBankname());
        $this->assertSame('TESTNL2A', $bank->getBiccode());
        $this->assertSame('Place 2000', $bank->getCity());
        $this->assertSame('NL', $bank->getCountry());
        $this->assertSame('NL13TEST0123456789', $bank->getIban());
        $this->assertSame('', $bank->getNatbiccode());
        $this->assertSame('', $bank->getPostcode());
        $this->assertSame('', $bank->getState());
        $this->assertSame('', $bank->getAddressField2());
        $this->assertSame('', $bank->getAddressField3());

        $this->assertSame('2000', $supplier->getCode());
        $this->assertSame('c6c05844-c075-4b51-a4ca-6f30f45d5a12', $supplier->getUID());
        $this->assertSame('true', $supplier->getInUse());
        $this->assertSame('normal', $supplier->getBehaviour());
        $this->assertSame('4', $supplier->getTouched());
        $this->assertSame('0', $supplier->getBeginPeriod());
        $this->assertNull($supplier->getBeginYear());
        $this->assertSame('0', $supplier->getEndPeriod());
        $this->assertSame('0', $supplier->getEndYear());
        $this->assertSame('false', $supplier->getEditDimensionName());
    }

    public function testListAllSuppliersWorks()
    {
        $response = Response::fromString(file_get_contents(__DIR__ . '/resources/supplierListResponse.xml'));

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(\PhpTwinfield\Request\Catalog\Dimension::class))
            ->willReturn($response);

        $suppliers = $this->supplierApiConnector->listAll($this->office);

        $this->assertCount(3, $suppliers);

        $this->assertArrayHasKey('1000', $suppliers);
        $this->assertSame('John Doe', $suppliers['1000']['name']);

        $this->assertArrayHasKey('1001', $suppliers);
        $this->assertSame('B. Terwel', $suppliers['1001']['name']);

        $this->assertArrayHasKey('1002', $suppliers);
        $this->assertSame('Hr E G H Küppers en/of MW M.J. Küppers-Veeneman', $suppliers['1002']['name']);
    }

    public function testSendSupplierWorks()
    {
        $supplier = new Supplier();
        $supplier->setOffice(Office::fromCode('001'));
        $supplier->setName('Supplier 0');
        $supplier->setDueDays('30');
        $supplier->setPayAvailable(true);
        $supplier->setPayCode('SEPANLDD');

        $address = new SupplierAddress();
        $address->setID('1');
        $address->setType('invoice');
        $address->setDefault(true);
        $address->setName('Supplier 0');
        $address->setCountry('NL');
        $address->setCity('Place');
        $address->setPostcode('1000');
        $address->setTelephone('010-123452000');
        $address->setFax('010-12342000');
        $address->setEmail('info@example.com');
        $address->setField1('Supplier 1');
        $address->setField2('Streetname part 1 - 1');
        $address->setField3('Streetname part 1 - 2');
        $supplier->addAddress($address);

        $bank = new SupplierBank();
        $bank->setDefault(true);
        $bank->setAscription('Supplier 1');
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
        $supplier->addBank($bank);

        $this->processXmlService
            ->expects($this->once())
            ->method("sendDocument")
            ->with($this->isInstanceOf(SuppliersDocument::class))
            ->willReturnCallback(function (SuppliersDocument $suppliersDocument): Response {
                $this->assertXmlStringEqualsXmlString(
                    file_get_contents(__DIR__ . '/resources/supplierSendRequest.xml'),
                    $suppliersDocument->saveXML()
                );

                return $this->getSuccessfulResponse();
            });

        $this->supplierApiConnector->send($supplier);
    }
}
