<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\TransactionHeader;

/**
 * Title: Transaction header XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class TransactionHeaderParser {
	public static function parse(\SimpleXMLElement $xml) {
		$header = new TransactionHeader();

		$header->setOffice(filter_var($xml->office, FILTER_SANITIZE_STRING));
		$header->setNumber(filter_var($xml->number, FILTER_SANITIZE_STRING));
		
		return $header;
	}
}
