<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\Transaction;

/**
 * Title: Transaction XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class TransactionParser {
	public static function parse(\SimpleXMLElement $xml) {
		$transaction = new Transaction();
		
		$transaction->setHeader(TransactionHeaderParser::parse($xml->header));

		return $transaction;
	}
}
