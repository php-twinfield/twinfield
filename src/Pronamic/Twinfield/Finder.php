<?php

namespace Pronamic\Twinfield;

/**
 * Title: Finder
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Finder {
	const WEB_SERVICE = '/webservices/finder.asmx';

	const WSDL_FILE = '/webservices/finder.asmx?wsdl';

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Constructs and initialize an finder
	 * 
	 * @param string $cluster
	 * @param unknown_type $header
	 */
	public function __construct($cluster, $header) {
		$this->soapClient = new \SoapClient($cluster . self::WSDL_FILE, array(
			'trace' => 1 ,
			'classmap' => array(
				'SearchResponse' => __NAMESPACE__ . '\SearchResponse' , 
				'FinderData' => __NAMESPACE__ . '\FinderData' , 
				'ArrayOfArrayOfString' => __NAMESPACE__ . '\ArrayOfArrayOfString' ,
				'ArrayOfString' => __NAMESPACE__ . '\ArrayOfString'
			) , /*
			'typemap' => array(
				array(
					'type_ns' => Twinfield::XML_NAMESPACE , 
					'type_name' => 'ArrayOfString' , 
					'to_xml' => __NAMESPACE__ . '\ArrayOfString::toXml' ,
					'from_xml' => __NAMESPACE__ . '\ArrayOfString::fromXml' 
				) , 
				array(
					'type_ns' => Twinfield::XML_NAMESPACE , 
					'type_name' => 'ArrayOfArrayOfString' , 
					'to_xml' => __NAMESPACE__ . '\ArrayOfArrayOfString::toXml' ,
					'from_xml' => __NAMESPACE__ . '\ArrayOfArrayOfString::fromXml' 
				)
			)*/
		));

		$this->soapInputHeader = $header;
	}

	///////////////////////////////////////////////////////////////////////////	

	/**
	 * Search
	 * 
	 * @param Search $search
	 * @return SearchResponse
	 */
	public function search(Search $search) {
/*		
		echo '<pre>';
		var_dump($this->soapClient->__getFunctions());
		echo '</pre>';
		echo '<pre>';
		var_dump($this->soapClient->__getTypes());
		echo '</pre>';
*/
		$searchResponse = $this->soapClient->__soapCall('Search', array($search), null, $this->soapInputHeader);
/*
echo "REQUEST:<br /><pre>" . htmlentities($this->soapClient->__getLastRequest()) . "</pre><br />";
echo "RESPONSE:<br /><pre>" . htmlentities($this->soapClient->__getLastResponse()) . "</pre><br />";
echo '<pre>'; var_dump($searchResponse); echo '</pre>';
*/

		return $searchResponse;
	}
}
