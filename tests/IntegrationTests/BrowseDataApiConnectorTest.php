<?php

namespace PhpTwinfield\IntegrationTests;

use PhpTwinfield\ApiConnectors\BrowseDataApiConnector;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseDefinition;
use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\Response\Response;

class BrowseDataApiConnectorTest extends BaseIntegrationTest
{
    /**
     * @var BrowseDataApiConnector|\PHPUnit_Framework_MockObject_MockObject
     */
    private $browseDataApiConnector;


    protected function setUp()
    {
        parent::setUp();

        $this->browseDataApiConnector = new BrowseDataApiConnector($this->connection);
    }

    public function testGetBrowseDefinition()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__ . "/resources/browseDefinitionGetResponse.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $browseDefinition = $this->browseDataApiConnector->getBrowseDefinition('000');

        $this->assertInstanceOf(BrowseDefinition::class, $browseDefinition);

        $this->assertEquals('001', $browseDefinition->getOffice()->getCode());
        $this->assertEquals('000', $browseDefinition->getCode());
        $this->assertEquals('General ledger transactions', $browseDefinition->getName());
        $this->assertEquals('General ledger transactions', $browseDefinition->getShortName());
        $this->assertEquals(true, $browseDefinition->isVisible());

        $column1 = $browseDefinition->getColumns()[1];
        $this->assertEquals(1, $column1->getId());
        $this->assertEquals('fin.trs.head.yearperiod', $column1->getField());
        $this->assertEquals('Period', $column1->getLabel());
        $this->assertEquals(true, $column1->isVisible());
        $this->assertEquals(true, $column1->isAsk());
        $this->assertEquals('between', $column1->getOperator()->getValue());
        $this->assertEquals('$DEFAULT$', $column1->getFrom());
        $this->assertEquals('$DEFAULT$', $column1->getTo());

        $column2 = $browseDefinition->getColumns()[2];
        $this->assertEquals(2, $column2->getId());
        $this->assertEquals('fin.trs.head.code', $column2->getField());
        $this->assertEquals('Transaction type', $column2->getLabel());
        $this->assertEquals(true, $column2->isVisible());
        $this->assertEquals(true, $column2->isAsk());
        $this->assertEquals('equal', $column2->getOperator()->getValue());
        $this->assertEquals(null, $column2->getFrom());
        $this->assertEquals(null, $column2->getTo());

        $column3 = $browseDefinition->getColumns()[3];
        $this->assertEquals(3, $column3->getId());
        $this->assertEquals('fin.trs.head.shortname', $column3->getField());
        $this->assertEquals('Name', $column3->getLabel());
        $this->assertEquals(true, $column3->isVisible());
        $this->assertEquals(false, $column3->isAsk());
        $this->assertEquals('none', $column3->getOperator()->getValue());
        $this->assertEquals(null, $column3->getFrom());
        $this->assertEquals(null, $column3->getTo());

        $column4 = $browseDefinition->getColumns()[4];
        $this->assertEquals(4, $column4->getId());
        $this->assertEquals('fin.trs.head.number', $column4->getField());
        $this->assertEquals('Trans. no.', $column4->getLabel());
        $this->assertEquals(true, $column4->isVisible());
        $this->assertEquals(true, $column4->isAsk());
        $this->assertEquals('between', $column4->getOperator()->getValue());
        $this->assertEquals(null, $column4->getFrom());
        $this->assertEquals(null, $column4->getTo());

        $column5 = $browseDefinition->getColumns()[5];
        $this->assertEquals(5, $column5->getId());
        $this->assertEquals('fin.trs.head.status', $column5->getField());
        $this->assertEquals('Status', $column5->getLabel());
        $this->assertEquals(true, $column5->isVisible());
        $this->assertEquals(true, $column5->isAsk());
        $this->assertEquals('equal', $column5->getOperator()->getValue());
        $this->assertEquals('normal', $column5->getFrom());
        $this->assertEquals(null, $column5->getTo());

        $column6 = $browseDefinition->getColumns()[6];
        $this->assertEquals(6, $column6->getId());
        $this->assertEquals('fin.trs.line.dim1', $column6->getField());
        $this->assertEquals('General ledger', $column6->getLabel());
        $this->assertEquals(true, $column6->isVisible());
        $this->assertEquals(true, $column6->isAsk());
        $this->assertEquals('between', $column6->getOperator()->getValue());
        $this->assertEquals(null, $column6->getFrom());
        $this->assertEquals(null, $column6->getTo());

        $column7 = $browseDefinition->getColumns()[7];
        $this->assertEquals(7, $column7->getId());
        $this->assertEquals('fin.trs.line.dim1name', $column7->getField());
        $this->assertEquals('Name', $column7->getLabel());
        $this->assertEquals(true, $column7->isVisible());
        $this->assertEquals(false, $column7->isAsk());
        $this->assertEquals('none', $column7->getOperator()->getValue());
        $this->assertEquals(null, $column7->getFrom());
        $this->assertEquals(null, $column7->getTo());
    }

    public function testGetBrowseFields()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__ . "/resources/browseFieldsGetResponse.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $browseFields = $this->browseDataApiConnector->getBrowseFields();

        $this->assertCount(5, $browseFields);

        $field1 = $browseFields[0];
        $this->assertEquals('fin.trs.head.bankcode', $field1->getCode());
        $this->assertEquals('special', $field1->getDataType());
        $this->assertCount(2, $field1->getOptions());
        $this->assertEquals('BNKABN', $field1->getOptions()[0]->getCode());
        $this->assertEquals(null, $field1->getOptions()[0]->getName());
        $this->assertEquals('BNKRABO', $field1->getOptions()[1]->getCode());
        $this->assertEquals(null, $field1->getOptions()[1]->getName());

        $field2 = $browseFields[1];
        $this->assertEquals('fin.trs.head.banktype', $field2->getCode());
        $this->assertEquals('special', $field2->getDataType());
        $this->assertCount(2, $field2->getOptions());
        $this->assertEquals('bank', $field2->getOptions()[0]->getCode());
        $this->assertEquals('Bank', $field2->getOptions()[0]->getName());
        $this->assertEquals('cashbook', $field2->getOptions()[1]->getCode());
        $this->assertEquals('Kas', $field2->getOptions()[1]->getName());

        $field3 = $browseFields[2];
        $this->assertEquals('fin.trs.head.number', $field3->getCode());
        $this->assertEquals('decimal', $field3->getDataType());
        $this->assertCount(0, $field3->getOptions());

        $field4 = $browseFields[3];
        $this->assertEquals('fin.trs.head.status', $field4->getCode());
        $this->assertEquals('special', $field4->getDataType());
        $this->assertCount(5, $field4->getOptions());
        $this->assertEquals('draft', $field4->getOptions()[0]->getCode());
        $this->assertEquals('Draft', $field4->getOptions()[0]->getName());
        $this->assertEquals('temporary', $field4->getOptions()[1]->getCode());
        $this->assertEquals('Provisional', $field4->getOptions()[1]->getName());
        $this->assertEquals('final', $field4->getOptions()[2]->getCode());
        $this->assertEquals('Final', $field4->getOptions()[2]->getName());
        $this->assertEquals('inuse', $field4->getOptions()[3]->getCode());
        $this->assertEquals('In use', $field4->getOptions()[3]->getName());
        $this->assertEquals('normal', $field4->getOptions()[4]->getCode());
        $this->assertEquals('Provisional & Final', $field4->getOptions()[4]->getName());

        $field5 = $browseFields[4];
        $this->assertEquals('fin.trs.line.debitcredit', $field5->getCode());
        $this->assertEquals('special', $field5->getDataType());
        $this->assertCount(2, $field5->getOptions());
        $this->assertEquals('credit', $field5->getOptions()[0]->getCode());
        $this->assertEquals('Credit', $field5->getOptions()[0]->getName());
        $this->assertEquals('debit', $field5->getOptions()[1]->getCode());
        $this->assertEquals('Debet', $field5->getOptions()[1]->getName());
    }

    public function testGetBrowseData()
    {
        $response = Response::fromString(file_get_contents(
            __DIR__ . "/resources/browseDataGetResponse.xml"
        ));

        $this->processXmlService->expects($this->once())
            ->method("sendDocument")
            ->willReturn($response);

        $columns[] = (new BrowseColumn())
            ->setField('fin.trs.head.yearperiod')
            ->setLabel('Period')
            ->setVisible(true)
            ->setAsk(true)
            ->setOperator(BrowseColumnOperator::BETWEEN())
            ->setFrom('2013/01')
            ->setTo('2013/12');

        $browseData = $this->browseDataApiConnector->getBrowseData('000', $columns);

        // Headers
        $this->assertCount(8, $browseData->getHeaders());
        $this->assertEquals('fin.trs.head.yearperiod', $browseData->getHeaders()[0]->getCode());
        $this->assertEquals('Period', $browseData->getHeaders()[0]->getLabel());
        $this->assertEquals('String', $browseData->getHeaders()[0]->getType());
        $this->assertEquals(false, $browseData->getHeaders()[0]->isHideForUser());

        // Rows
        $row1 = $browseData->getRows()[0];
        $this->assertCount(6, $browseData->getRows());
        $this->assertEquals('001', $row1->getOffice()->getCode());
        $this->assertEquals('BNK', $row1->getCode());
        $this->assertEquals('201300001', $row1->getNumber());
        $this->assertEquals('2', $row1->getLine());

        // Cells
        $cell1 = $row1->getCells()[0];
        $this->assertCount(8, $row1->getCells());
        $this->assertEquals('String', $cell1->getType());
        $this->assertEquals('fin.trs.head.yearperiod', $cell1->getField());
        $this->assertEquals(false, $cell1->isHideForUser());
        $this->assertEquals('2013/04', $cell1->getValue());

        $cell4 = $row1->getCells()[3];
        $this->assertEquals('Decimal', $cell4->getType());
        $this->assertEquals('fin.trs.head.number', $cell4->getField());
        $this->assertEquals(false, $cell4->isHideForUser());
        $this->assertEquals(201300001, $cell4->getValue());

        $cell7 = $row1->getCells()[6];
        $this->assertEquals('Value', $cell7->getType());
        $this->assertEquals('fin.trs.line.valuesigned', $cell7->getField());
        $this->assertEquals(false, $cell7->isHideForUser());
        $this->assertEquals(-121.00, $cell7->getValue());
    }
}