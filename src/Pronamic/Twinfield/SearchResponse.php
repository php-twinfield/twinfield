<?php

namespace Pronamic\Twinfield;

/**
 * Title: Search response
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class SearchResponse {
	/**
	 * Get the data of this search response
	 * 
	 * @return FinderData
	 */
	public function getData() {
		return $this->data;
	}
}
