<?php

namespace Pronamic\Twinfield\Invoice;

class InvoiceLine {

	private $ID;

	private $quantity;
	private $article;
	private $subArticle;
	private $description;
	private $unitsPriceExcl;
	private $units;
	private $allowDiscountOrPremium;
	private $valueExcl;
	private $vatValue;
	private $valueInc;
	private $vatCode;
	private $freeText1;
	private $freeText2;

	public function __construct( $quantity = null, $article = null, $freeText1 = null, $freeText2 = null ) {
		$this->ID = uniqid();

		$this->quantity = $quantity;
		$this->article = $article;
		$this->freeText1 = $freeText1;
		$this->freeText2 = $freeText2;
	}

	public function getID() {
		return $this->ID;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity( $quantity ) {
		$this->quantity = $quantity;
		return $this;
	}

	public function getArticle() {
		return $this->article;
	}

	public function setArticle( $article ) {
		$this->article = $article;
		return $this;
	}

	public function getSubArticle() {
		return $this->subArticle;
	}

	public function setSubArticle( $subArticle ) {
		$this->subArticle = $subArticle;
		return $this;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription( $description ) {
		$this->description = $description;
		return $this;
	}

	public function getUnitsPriceExcl() {
		return $this->unitsPriceExcl;
	}

	public function setUnitsPriceExcl( $unitsPriceExcl ) {
		$this->unitsPriceExcl = $unitsPriceExcl;
		return $this;
	}

	public function getUnits() {
		return $this->units;
	}

	public function setUnits( $units ) {
		$this->units = $units;
		return $this;
	}

	public function getAllowDiscountOrPremium() {
		return $this->allowDiscountOrPremium;
	}

	public function setAllowDiscountOrPremium( $allowDiscountOrPremium ) {
		$this->allowDiscountOrPremium = $allowDiscountOrPremium;
		return $this;
	}

	public function getValueExcl() {
		return $this->valueExcl;
	}

	public function setValueExcl( $valueExcl ) {
		$this->valueExcl = $valueExcl;
		return $this;
	}

	public function getVatValue() {
		return $this->vatValue;
	}

	public function setVatValue( $vatValue ) {
		$this->vatValue = $vatValue;
		return $this;
	}

	public function getValueInc() {
		return $this->valueInc;
	}

	public function setValueInc( $valueInc ) {
		$this->valueInc = $valueInc;
		return $this;
	}

	public function getVatCode() {
		return $this->vatCode;
	}

	public function setVatCode( $vatCode ) {
		$this->vatCode = $vatCode;
		return $this;
	}

	public function getFreeText1() {
		return $this->freeText1;
	}

	public function setFreeText1( $freeText1 ) {
		$this->freeText1 = $freeText1;
		return $this;
	}

	public function getFreeText2() {
		return $this->freeText2;
	}

	public function setFreeText2( $freeText2 ) {
		$this->freeText2 = $freeText2;
		return $this;
	}



}