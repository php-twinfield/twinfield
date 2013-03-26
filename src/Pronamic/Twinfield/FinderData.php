<?php

namespace Pronamic\Twinfield;

/**
 * Title: Finder data
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class FinderData {
	public function getTotalRows() {
		return $this->TotalRows;
	}

	public function getColumns() {
		return $this->Columns;
	}

	public function getItems() {
		return $this->Items;
	}
}
