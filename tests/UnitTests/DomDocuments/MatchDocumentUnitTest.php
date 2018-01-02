<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\DomDocuments\MatchDocument;
use PhpTwinfield\MatchCode;
use PhpTwinfield\MatchLine;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Office;
use PhpTwinfield\WriteOffType;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
class MatchDocumentUnitTest extends \PHPUnit\Framework\TestCase
{

    public function testFullPayment()
    {
        $matchset = new MatchSet();
        $matchset->setOffice(Office::fromCode("001"));
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        $line1 = new MatchLine();
        $line1->setTranscode("CASH");
        $line1->setTransnumber(201300013);
        $line1->setTransline(2);
        $matchset->addLine($line1);

        $line2 = new MatchLine();
        $line2->setTranscode("SLS");
        $line2->setTransnumber(201300069);
        $line2->setTransline(1);
        $matchset->addLine($line2);

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
        $matchset->setOffice(Office::fromCode("001"));
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        $line1 = new MatchLine();
        $line1->setTranscode("CASH");
        $line1->setTransnumber(201300014);
        $line1->setTransline(2);
        $matchset->addLine($line1);

        $line2 = new MatchLine();
        $line2->setTranscode("SLS");
        $line2->setTransnumber(201300070);
        $line2->setTransline(1);
        $line2->setMatchvalue(Money::EUR(11900));
        $matchset->addLine($line2);

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
        $matchset->setOffice(Office::fromCode("001"));
        $matchset->setMatchCode(MatchCode::CUSTOMERS());
        $matchset->setMatchDate(new \DateTimeImmutable("2013-02-11"));

        $line1 = new MatchLine();
        $line1->setTranscode("CASH");
        $line1->setTransnumber(201300015);
        $line1->setTransline(2);
        $matchset->addLine($line1);

        $line2 = new MatchLine();
        $line2->setTranscode("SLS");
        $line2->setTransnumber(201300071);
        $line2->setTransline(1);
        $line2->setWriteOff(Money::EUR(200), WriteOffType::DISCOUNT());
        $matchset->addLine($line2);

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