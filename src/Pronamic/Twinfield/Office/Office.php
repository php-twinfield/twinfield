<?php

namespace Pronamic\Twinfield\Office;

/**
 * Title: Office
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Office {
	/**
	 * The code of this office
	 *
	 * @var string
	 */
	private $code;

	/**
	 * The name of this office
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The shortname of this office
	 *
	 * @var string
	 */
	private $shortName;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Constructs and initializes an office object
	 */
	public function __construct() {

	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the code of this office
	 *
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Set the code of this office
	 *
	 * @return string
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the name of this office
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the name of this office
	 *
	 * @return string
	 */
	public function setName($name) {
		$this->name = $name;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the shortname of this office
	 *
	 * @return string
	 */
	public function getShortName() {
		return $this->shortName;
	}

	/**
	 * Set the shortname of this office
	 *
	 * @return string
	 */
	public function setShortName($shortName) {
		$this->shortName = $shortName;
	}
}
