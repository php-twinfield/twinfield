<?php

namespace Pronamic\Twinfield;

/**
 * Title: Address
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Address {
	/**
	 * The name of the address
	 * 
	 * @var string
	 */
	private $name;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Construct and initializes an address
	 */
	public function __construct() {
		
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the name of this address
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this address
	 * 
	 * @param string
	 */
	public function setName($name) {
		$this->name = $name;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the city of this address
	 * 
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Set the name of this address
	 * 
	 * @param string
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the postcode of this address
	 * 
	 * @return string
	 */
	public function getPostcode() {
		return $this->postcode;
	}

	/**
	 * Set the postcode of this address
	 * 
	 * @param string
	 */
	public function setPostcode($postcode) {
		$this->postcode = $postcode;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the telephone of this address
	 * 
	 * @return string
	 */
	public function getTelephone() {
		return $this->telephone;
	}

	/**
	 * Set the telephone of this address
	 * 
	 * @param string
	 */
	public function setTelephone($telephone) {
		$this->telephone = $telephone;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the telefax of this address
	 * 
	 * @return string
	 */
	public function getTelefax() {
		return $this->telefax;
	}

	/**
	 * Set the telefax of this address
	 * 
	 * @param string
	 */
	public function setTelefax($telefax) {
		$this->telefax = $telefax;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the e-mail of this address
	 * 
	 * @return string
	 */
	public function getEMail() {
		return $this->eMail;
	}

	/**
	 * Set the e-mail of this address
	 * 
	 * @param string
	 */
	public function setEMail($eMail) {
		$this->eMail = $eMail;
	}
}
