<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseSortField;
use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\Enums\Order;
use PhpTwinfield\Request\BrowseData;
use PHPUnit\Framework\TestCase;

class BrowseDataUnitTest extends TestCase
{
    public function testConstructor()
    {
        $columns[] = (new BrowseColumn())
            ->setField('fin.trs.head.yearperiod')
            ->setLabel('Period')
            ->setVisible(true)
            ->setAsk(true)
            ->setOperator(BrowseColumnOperator::BETWEEN())
            ->setFrom('2013/01')
            ->setTo('2013/12');

        $sortFields[] = (new BrowseSortField('fin.trs.head.yearperiod', Order::DESCENDING()));

        $browseData = new BrowseData('000', $columns, $sortFields);

        $this->assertXmlStringEqualsXmlString('
            <columns code="000">
                <sort>
                    <field order="descending">fin.trs.head.yearperiod</field>
                </sort>
                <column>
                    <field>fin.trs.head.yearperiod</field>
                    <label>Period</label>
                    <visible>true</visible>
                    <ask>true</ask>
                    <operator>between</operator>
                    <from>2013/01</from>
                    <to>2013/12</to>
                </column>
            </columns>', $browseData->saveXML()
        );
    }

    public function testFailureResponseForConstructorWithWrongColumnObjects()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected an instance of PhpTwinfield\BrowseColumn. Got: PhpTwinfield\BrowseSortField');

        new BrowseData('000', [new BrowseSortField('test')]);
    }

    public function testFailureResponseForConstructorWithWrongSortFieldsObjects()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected an instance of PhpTwinfield\BrowseSortField. Got: PhpTwinfield\BrowseColumn');

        $columns[] = (new BrowseColumn())
            ->setField('fin.trs.head.yearperiod')
            ->setLabel('Period')
            ->setVisible(true)
            ->setAsk(true)
            ->setOperator(BrowseColumnOperator::BETWEEN())
            ->setFrom('2013/01')
            ->setTo('2013/12');

        new BrowseData('000', $columns, $columns);
    }
}