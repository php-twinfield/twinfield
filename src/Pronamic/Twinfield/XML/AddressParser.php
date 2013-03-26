<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\Address;

/**
 * Title: Address XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class AddressParser {
	public static function parse(\SimpleXMLElement $xml) {
		$address = new Address();

		$address->setName((string) $xml->name);
		$address->setCity((string) $xml->city);
		$address->setPostcode((string) $xml->postcode);
		$address->setTelephone((string) $xml->telephone);
		$address->setTelefax((string) $xml->telefax);
		$address->setEMail((string) $xml->email);

		return $address;
	}
}
