<?php

namespace Pronamic\Twinfield\Customer;

class Customer {

	private $ID;
	private $UID;
	private $name;
	private $type;
	private $inUse;
	private $behaviour;
	private $touched;
	private $beginPeriod;
	private $beginYear;
	private $endPeriod;
	private $endYear;
	private $website;
	private $cocNumber;
	private $vatNumber;
	private $editDimensionName;
	private $financials = array();
	private $addresses = array();
	private $groups;

	public function getID() {
		return $this->ID;
	}

	public function setID( $ID ) {
		$this->ID = $ID;
		return $this;
	}

	public function getUID() {
		return $this->UID;
	}

	public function setUID( $UID ) {
		$this->UID = $UID;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName( $name ) {
		$this->name = $name;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType( $type ) {
		$this->type = $type;
		return $this;
	}

	public function getInUse() {
		return $this->inUse;
	}

	public function setInUse( $inUse ) {
		$this->inUse = $inUse;
		return $this;
	}

	public function getBehaviour() {
		return $this->behaviour;
	}

	public function setBehaviour( $behaviour ) {
		$this->behaviour = $behaviour;
		return $this;
	}

	public function getTouched() {
		return $this->touched;
	}

	public function setTouched( $touched ) {
		$this->touched = $touched;
		return $this;
	}

	public function getBeginPeriod() {
		return $this->beginPeriod;
	}

	public function setBeginPeriod( $beginPeriod ) {
		$this->beginPeriod = $beginPeriod;
		return $this;
	}

	public function getBeginYear() {
		return $this->beginYear;
	}

	public function setBeginYear( $beginYear ) {
		$this->beginYear = $beginYear;
		return $this;
	}

	public function getEndPeriod() {
		return $this->endPeriod;
	}

	public function setEndPeriod( $endPeriod ) {
		$this->endPeriod = $endPeriod;
		return $this;
	}

	public function getEndYear() {
		return $this->endYear;
	}

	public function setEndYear( $endYear ) {
		$this->endYear = $endYear;
		return $this;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function setWebsite( $website ) {
		$this->website = $website;
		return $this;
	}

	public function getCocNumber() {
		return $this->cocNumber;
	}

	public function setCocNumber( $cocNumber ) {
		$this->cocNumber = $cocNumber;
		return $this;
	}

	public function getVatNumber() {
		return $this->vatNumber;
	}

	public function setVatNumber( $vatNumber ) {
		$this->vatNumber = $vatNumber;
		return $this;
	}

	public function getEditDimensionName() {
		return $this->editDimensionName;
	}

	public function setEditDimensionName( $editDimensionName ) {
		$this->editDimensionName = $editDimensionName;
		return $this;
	}

	public function getFinancials() {
		return $this->financials;
	}

	public function setFinancials( $financials ) {
		$this->financials = $financials;
		return $this;
	}

	public function getAddresses() {
		return $this->addresses;
	}

	public function addAddress(CustomerAddress $address) {
		$this->addresses[] = $address;
		return $this;
	}

	public function getGroups() {
		return $this->groups;
	}

	public function setGroups( $groups ) {
		$this->groups = $groups;
		return $this;
	}
}
