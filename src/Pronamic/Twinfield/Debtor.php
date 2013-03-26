<?php

namespace Pronamic\Twinfield;

/**
 * Title: Debtor
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Debtor {
	/**
	 * The office where this debtor is part of
	 * 
	 * @var Office
	 */
	private $office;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * The name of the debtor
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * The shortname of the debtor
	 * 
	 * @var string
	 */
	private $shortName;

	/**
	 * The website of the debtor
	 * 
	 * @var string
	 */
	private $website;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Addresses of the debtor
	 * 
	 * @var array
	 */
	private $addresses;

	/**
	 * Banks of the debtor
	 * 
	 * @var array
	 */
	private $banks;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Construct and initializes an debtor
	 */
	public function __construct() {
		$this->addresses = array();
		$this->banks = array();
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the office where this debtor is part of
	 * 
	 * @return Office
	 */
	public function getOffice() {
		return $this->office;
	}

	/**
	 * Set the office where this debtor is part of
	 * 
	 * @param Office $office
	 */
	public function setOffice(Office $office) {
		$this->office = $office;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the name of this debtor
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this debtor
	 * 
	 * @param string
	 */
	public function setName($name) {
		$this->name = $name;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the website of this debtor
	 * 
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * Set the website of this debtor
	 * 
	 * @param string
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the addresses of this debtor
	 * 
	 * @return array
	 */
	public function getAddresses() {
		return $this->addresses;
	}

	/**
	 * Set the addresses of this debtor
	 * 
	 * @param array
	 */
	public function setAddresses(array $addresses) {
		$this->addresses = $addresses;
	}
}
