<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\SalesInvoice;

/**
 * Title: Sales invoice XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoiceParser {
	public static function parse(\SimpleXMLElement $xml) {
		$salesInvoice = new SalesInvoice();

		$salesInvoice->setHeader(SalesInvoiceHeaderParser::parse($xml->header));

		foreach($xml->lines->line as $xml) {
			$line = SalesInvoiceLineParser::parse($xml);

			$salesInvoice->addLine($line);
		}

		return $salesInvoice;
	}
}
