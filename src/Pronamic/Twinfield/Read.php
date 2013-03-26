<?php

namespace Pronamic\Twinfield;

/**
 * Title: Read
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Read {
	/**
	 * Returns the article definition of a particular article
	 * 
	 * @var string
	 */
	const TYPE_ARTICLE = 'article';

	/**
	 * Returns the browse definition of a particular
	 * 
	 * @var string
	 */
	const TYPE_BROWSE = 'browse';

	/**
	 * Returns the dimension definition of a particular dimension
	 * 
	 * @var string
	 */
	const TYPE_DIMENSIONS = 'dimensions';

	/**
	 * Returns the office settings
	 * 
	 * @var string
	 */
	const TYPE_OFFICE = 'office';

	/**
	 * Returns the project rate settings
	 * 
	 * @var string
	 */
	const TYPE_PROJECT_RATE = 'projectrate';

	/**
	 * Returns the sales invoice details of an invoice
	 * 
	 * @var string
	 */
	const TYPE_SALES_INVOICE = 'salesinvoice';

	/**
	 * Returns the transaction details of a particular transaction
	 * 
	 * @var string
	 */
	const TYPE_TRANSACTION = 'transaction';

	/**
	 * Returns the user settings
	 * 
	 * @var string
	 */
	const TYPE_USER = 'user';

	/**
	 * Returns the VAT settings
	 * 
	 * @var string
	 */
	const TYPE_VAT = 'vat';

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Dimension type debtor
	 * 
	 * @var string
	 */
	const DIMENSION_TYPE_DEBTOR = 'DEB';
}
