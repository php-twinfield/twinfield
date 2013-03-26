<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\Office;

/**
 * Title: Office XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class OfficeParser {
	public static function parse(\SimpleXMLElement $xml) {
		$office = new Office();

		$office->setCode((string) $xml);
		$office->setName((string) $xml['name']);
		$office->setShortName((string) $xml['shortname']);

		return $office;
	}
}
