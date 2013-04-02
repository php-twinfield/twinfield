<?php

namespace Pronamic\Twinfield\Customer;

class CustomerAddress {
	private $ID;
	private $type;
	private $default;
	private $name;
	private $country;
	private $city;
	private $postcode;
	private $telephone;
	private $fax;
	private $email;
	private $field1;
	private $field2;
	private $field3;
	private $field4;
	private $field5;
	private $field6;

	public function getID() {
		return $this->ID;
	}

	public function setID( $ID ) {
		$this->ID = $ID;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
		return $this;
	}

	public function getDefault() {
		return $this->default;
	}

	public function setDefault( $default ) {
		$this->default = $default;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
		return $this;
	}

	public function getCountry() {
		return $this->country;
	}

	public function setCountry( $country ) {
		$this->country = $country;
		return $this;
	}

	public function getCity() {
		return $this->city;
	}

	public function setCity( $city ) {
		$this->city = $city;
		return $this;
	}

	public function getPostcode() {
		return $this->postcode;
	}

	public function setPostcode( $postcode ) {
		$this->postcode = $postcode;
		return $this;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function setTelephone( $telephone ) {
		$this->telephone = $telephone;
		return $this;
	}

	public function getFax() {
		return $this->fax;
	}

	public function setFax( $fax ) {
		$this->fax = $fax;
		return $this;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail( $email ) {
		$this->email = $email;
		return $this;
	}

	public function getField1() {
		return $this->field1;
	}

	public function setField1( $field1 ) {
		$this->field1 = $field1;
		return $this;
	}

	public function getField2() {
		return $this->field2;
	}

	public function setField2( $field2 ) {
		$this->field2 = $field2;
		return $this;
	}

	public function getField3() {
		return $this->field3;
	}

	public function setField3( $field3 ) {
		$this->field3 = $field3;
		return $this;
	}

	public function getField4() {
		return $this->field4;
	}

	public function setField4( $field4 ) {
		$this->field4 = $field4;
		return $this;
	}

	public function getField5() {
		return $this->field5;
	}

	public function setField5( $field5 ) {
		$this->field5 = $field5;
		return $this;
	}

	public function getField6() {
		return $this->field6;
	}

	public function setField6( $field6 ) {
		$this->field6 = $field6;
		return $this;
	}
}