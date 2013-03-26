<?php

namespace Pronamic\Twinfield;

/**
 * Title: Search
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Search {
	/**
	 * Type dimension
	 * 
	 * @var string
	 */
	const TYPE_DIMENSION = 'DIM';

	///////////////////////////////////////////////////////////////////////////

	/** 
	 * Searches on the code or name field
	 * 
	 * @var int
	 */
	const FIELD_ALL_CODE_OR_NAME = 0;

	/**
	 * Searches only on the code field
	 * 
	 * @var int
	 */
	const FIELD_ALL_CODE = 1;

	/**
	 * Searched only on the name field
	 * 
	 * @var int
	 */
	const FIELD_ALL_NAME = 2;

	/**
	 * Searches in for a dimension bank account number
	 * 
	 * @var int
	 */
	const FIELD_DIM_BANK_ACCOUNT_NUMBER = 3;

	/**
	 * Searches in the dimensions address fields
	 * 
	 * @var int
	 */
	const FIELD_DIM_ADDRESS = 4;

	/**
	 * Searched for the customer code (only available for projects/activities)
	 * 
	 * @var int
	 */
	const FIELD_DIM_CUSTOMER_CODE = 3;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Indicator to return all rows
	 * @see Search->maxRows
	 * 
	 * @var int
	 */
	const ROWS_ALL = 0;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the type
	 * 
	 * @return string
	 */
	public function getInvoiceType() {
		return $this->type;
	}

	/**
	 * Set the type
	 * 
	 * @param string $type
	 */
	public function setInvoiceType($type) {
		$this->type = $type;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the pattern
	 * 
	 * @return string
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Set the pattern
	 * 
	 * @param string $pattern
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the field
	 * 
	 * @return int
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Set the field
	 * 
	 * @param int $field
	 */
	public function setField($field) {
		$this->field = $field;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get first row
	 * 
	 * @return int
	 */
	public function getFirstRow() {
		return $this->firstRow;
	}

	/**
	 * Set the first row
	 * 
	 * @param int $firstRow
	 */
	public function setFirstRow($firstRow) {
		$this->firstRow = $firstRow;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the max rows
	 * 
	 * @return int
	 */
	public function getMaxRows() {
		return $this->maxRows;
	}

	/**
	 * Set the max rows
	 * The maximum number of rows to return. When set to 0 all rows will be returned. Must be >= 0.
	 * 
	 * @param int $maxRows
	 */
	public function setMaxRows($maxRows) {
		$this->maxRows = $maxRows;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the options
	 * 
	 * @return array
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Set the options
	 * 
	 * @param array $options
	 */
	public function setOptions(array $options) {
		$this->options = $options;
	}
}
