<?php

namespace Pronamic\Twinfield;

/**
 * Title: Sales invoice line
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SalesInvoiceLine {
	public $id;

	public $article;

	public $subArticle;

	public $quantity;

	public $units;

	public $allowDiscountOrPremium;

	public $description;

	public $valueExcl;

	public $vatValue;

	public $valueInc;

	public $unitsPriceExcl;

	public $freeText1;

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initializes an sales invoice line object
	 */
	public function __construct() {
		
	}
}
