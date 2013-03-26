<?php

namespace Pronamic\Twinfield\XML;

/**
 * Title: Address XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class AddressesParser {
	public static function parse(\SimpleXMLElement $xml) {
		$addresses = array();

		foreach($xml->address as $element) {
			$addresses[] = AddressParser::parse($element);
		}

		return $addresses;
	}
}
