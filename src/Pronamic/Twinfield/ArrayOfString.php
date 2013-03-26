<?php

namespace Pronamic\Twinfield;

/**
 * Title: Array of string
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class ArrayOfString implements \IteratorAggregate {
	/**
	 * Constructs and initializes an array of array of string
	 */
	public function __construct($strings = null) {
		if(is_array($strings)) {
			$this->string = $strings;
		} elseif($strings === null) {
			$this->string = array();
		} else {
			$this->string = func_get_args();
		}
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Get iterator
	 * 
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new \ArrayIterator($this->string);
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Create an XML representation of the specified array
	 * 
	 * @param array $strings
	 * @return string
	 */
	public static function toXml($strings) {
		$xml = '<ArrayOfString>';

		foreach($strings as $string) {
			$xml .= '<string>' . $string . '</string>';
		}
		
		$xml .= '</ArrayOfString>';

		return $xml;
	}

	/**
	 * Create an array from the specified XML
	 *
	 * @param string $xml
	 * @return array
	 */
	public static function fromXml($xml) {
		$strings = array();

		$xml = simplexml_load_string($xml);

		if($xml !== false) {
			$strings = self::fromSimpleXml($xml);
		}

		return $strings;
	}

	/**
	 * Create an array from the specified simple XML
	 *
	 * @param \SimpleXMLElement $element
	 * @return array
	 */
	public static function fromSimpleXml(\SimpleXMLElement $element) {
		$strings = array();

		foreach($element->string as $string) {
			$strings[] = (string) $string;
		}

		return $strings;
	}
}
