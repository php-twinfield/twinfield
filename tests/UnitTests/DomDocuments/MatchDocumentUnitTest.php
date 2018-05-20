<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\DomDocuments\MatchDocument;
use PhpTwinfield\Enums\MatchCode;
use PhpTwinfield\Enums\WriteOffType;
use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchReference;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Office;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
class MatchDocumentUnitTest extends \PHPUnit\Framework\TestCase
{
    protected $office;

    protected function setUp()
    {
        parent::setUp();

        $this->office = Office::fromCode("001");
    }

    public function testFullPayment()
    {
        $matchset = new MatchSet();
        $matchset->setOffice($this->office);
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "CASH",
            201300013,
            2
        ));

        MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "SLS",
            201300069,
            1
        ));

        $matchdocument = new MatchDocument();
        $matchdocument->addMatchSet($matchset);

        $this->assertXmlStringEqualsXmlString(
            '<match>
   <set>
      <office>001</office>
      <matchcode>170</matchcode>
      <matchdate>20130211</matchdate>
      <lines>
         <line>
            <transcode>CASH</transcode>
            <transnumber>201300013</transnumber>
            <transline>2</transline>
         </line>
         <line>
            <transcode>SLS</transcode>
            <transnumber>201300069</transnumber>
            <transline>1</transline>
         </line>
      </lines>
   </set>
</match>',
            $matchdocument->saveXML()
        );
    }

    public function testPartialPayment()
    {
        $matchset = new MatchSet();
        $matchset->setOffice($this->office);
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "CASH",
            201300014,
            2
        ));

        MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "SLS",
            201300070,
            1
        ), Money::EUR(11900));

        $matchdocument = new MatchDocument();
        $matchdocument->addMatchSet($matchset);

        $this->assertXmlStringEqualsXmlString(
            '<match>
   <set>
      <office>001</office>
      <matchcode>170</matchcode>
      <matchdate>20130211</matchdate>
      <lines>
         <line>
            <transcode>CASH</transcode>
            <transnumber>201300014</transnumber>
            <transline>2</transline>
         </line>
         <line>
            <transcode>SLS</transcode>
            <transnumber>201300070</transnumber>
            <transline>1</transline>
            <matchvalue>119.00</matchvalue>
         </line>
      </lines>
   </set>
</match>',
            $matchdocument->saveXML()
        );
    }

    public function testDiscount()
    {
        $matchset = new MatchSet();
        $matchset->setOffice($this->office);
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "CASH",
            201300015,
            2
        ));

        $line2 = MatchLine::addToMatchSet($matchset, new MatchReference(
            $this->office,
            "SLS",
            201300071,
        1));
        $line2->setWriteOff(Money::EUR(200), WriteOffType::DISCOUNT());

        $matchdocument = new MatchDocument();
        $matchdocument->addMatchSet($matchset);

        $this->assertXmlStringEqualsXmlString(
            '<match>
   <set>
      <office>001</office>
      <matchcode>170</matchcode>
      <matchdate>20130211</matchdate>
      <lines>
         <line>
            <transcode>CASH</transcode>
            <transnumber>201300015</transnumber>
            <transline>2</transline>
         </line>
         <line>
            <transcode>SLS</transcode>
            <transnumber>201300071</transnumber>
            <transline>1</transline>
            <writeoff type="discount">2.00</writeoff>
         </line>
      </lines>
   </set>
</match>',
            $matchdocument->saveXML()
        );
    }
}