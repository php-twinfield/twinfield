<?php

namespace Pronamic\Twinfield\XML;

use Pronamic\Twinfield\SalesInvoiceLine;

/**
 * Title: Sales invoice line XML parser
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoiceLineParser {
	public static function parse(\SimpleXMLElement $xml) {
		$line = new SalesInvoiceLine();

		$line->id = filter_var($xml['id'], FILTER_SANITIZE_STRING);
		$line->article = filter_var($xml->article, FILTER_SANITIZE_STRING);
		$line->subArticle = filter_var($xml->subarticle, FILTER_SANITIZE_STRING);
		$line->quantity = filter_var($xml->quantity, FILTER_VALIDATE_FLOAT);
		$line->units = filter_var($xml->units, FILTER_VALIDATE_INT);
		$line->allowDiscountOrPremium = filter_var($xml->allowdiscountorpremium, FILTER_VALIDATE_BOOLEAN);
		$line->description = filter_var($xml->description, FILTER_SANITIZE_STRING);
		$line->valueExcl = filter_var($xml->valueexcl, FILTER_VALIDATE_FLOAT);
		$line->vatValue = filter_var($xml->vatvalue, FILTER_VALIDATE_FLOAT);
		$line->valueInc = filter_var($xml->valueinc, FILTER_VALIDATE_FLOAT);
		$line->unitsPriceExcl = filter_var($xml->unitspriceexcl, FILTER_VALIDATE_FLOAT);
		$line->freeText1 = filter_var($xml->freetext1, FILTER_SANITIZE_STRING);

		return $line;
	}
}
