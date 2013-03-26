<?php

namespace Pronamic\Twinfield;

/**
 * Title: Array of array of string
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class ArrayOfArrayOfString implements \IteratorAggregate {
	/**
	 * Constructs and initializes an array of array of string
	 */
	public function __construct($array = null) {
		if(is_array($array)) {
			$this->ArrayOfString = $array;
		} elseif($array === null) {
			$this->ArrayOfString = array();
		} else {
			$this->ArrayOfString = func_get_args();
		}
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get iterator
	 * 
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new \ArrayIterator($this->ArrayOfString);
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Create an XML representation of the specified array
	 * 
	 * @param array $arrays
	 * @return string
	 */
	public static function toXml($arrays) {
		$xml = '';

		foreach($arrays as $array) {
			$xml .= ArrayOfString::toXml($array);
		}

		return $xml;
	}

	/**
	 * Create an array from the specified XML
	 *
	 * @param string $xml
	 * @return array
	 */
	public static function fromXml($xml) {
		$arrays = array();

		$xml = simplexml_load_string($xml);

		if($xml !== false) {
			$arrays = self::fromSimpleXml($xml);
		}

		return $arrays;
	}

	/**
	 * Create an array from the specified simple XML
	 *
	 * @param \SimpleXMLElement $element
	 * @return array
	 */
	public static function fromSimpleXml(\SimpleXMLElement $element) {
		$arrays = array();

		foreach($element->ArrayOfString as $arrayOfString) {
			$arrays[] = ArrayOfString::fromSimpleXml($arrayOfString);
		}

		return $arrays;
	}
}
