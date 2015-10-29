<?php

namespace Pronamic\Twinfield\Factory;

/**
 * Use the finder web service in order to get all kind of master data from Twinfield.
 *
 * @note Since it is not possible to add the company code to the finder, make sure the correct company is set by using
 * either the SelectCompany function or adding the office option.
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Finder
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield
 */
abstract class FinderFactory extends ProcessXmlFactory
{
	const SEARCH_FIELD_CODE_OR_NAME = 0;
	const SEARCH_FIELD_CODE = 1;
	const SEARCH_FIELD_NAME = 2;
	const SEARCH_FIELD_DIMENSIONS_BANK_ACCOUNT_NUMBER = 3;
	const SEARCH_FIELD_DIMENSIONS_ADDRESS = 4;
	const SEARCH_FIELD_CUSTOMER_CODE = 3;
	const SEARCH_FIELD_DIMENSION_CODE_OR_NAME_INVOICE_AMOUNT_INVOICE_NUMBER = 0;
    const TYPE_ITEMS = 'ART';
    const TYPE_ASSET_METHODS = 'ASM';
    const TYPE_BUDGETS = 'BDS';
    const TYPE_CASHBOOKS_AND_BANKS = 'BNK';
    const TYPE_BILLING_SCHEDULE = 'BSN';
    const TYPE_CREDIT_MANAGEMENT_ACTION_CODES = 'CDA';
    const TYPE_CERTIFICATES = 'CER';
    const TYPE_LIST_OF_AVAILABLE_CHEQUES = 'CQT';
    const TYPE_COUNTRIES = 'CTR';
    const TYPE_CURRENCIES = 'CUR';
    const TYPE_DIMENSIONS_FINANCIALS = 'DIM';
    const TYPE_DIMENSIONS_MODIFIED_SINCE_OPTION = 'DIM';
    const TYPE_DIMENSIONS_PROJECTS = 'DIM';
    const TYPE_DIMENSION_TYPES = 'DMT';
    const TYPE_VAT_TYPES = 'DVT';
    const TYPE_FILTER_MAPPINGS = 'FLT';
    const TYPE_PAYMENT_FILES = 'FMT';
    const TYPE_DIMENSION_GROUPS = 'GRP';
    const TYPE_GATEWAYS = 'GWY';
    const TYPE_HIERARCHIES = 'HIE';
    const TYPE_HIERARCHY_NODES = 'HND';
    const TYPE_INVOICE_TYPES = 'INV';
    const TYPE_LIST_OF_AVAILABLE_INVOICES = 'IVT';
    const TYPE_MATCHING_TYPES = 'MAT';
    const TYPE_OFFICES = 'OFF';
    const TYPE_OFFICE_GROUPS = 'OFG';
    const TYPE_AVAILABLE_OFFICES_FOR_INTER_COMPANY_TRANSACTIONS = 'OIC';
    const TYPE_PAYMENT_TYPES = 'PAY';
    const TYPE_PAYING_IN_SLIPS = 'PIS';
    const TYPE_PERIODS = 'PRD';
    const TYPE_REPORTS = 'REP';
    const TYPE_WORD_TEMPLATES = 'REW';
    const TYPE_REMINDER_SCENARIOS = 'RMD';
    const TYPE_USER_ROLES = 'ROL';
    const TYPE_SUB_ITEMS = 'SAR';
    const TYPE_DISTRIBUTION_BY_PERIODS = 'SPM';
    const TYPE_TAX_GROUPS = 'TXG';
    const TYPE_TIME_QUANTITIES_TRANSACTION_TYPES = 'TEQ';
    const TYPE_TRANSACTION_TYPES = 'TRS';
    const TYPE_TIME_PROJECT_RATES = 'TRT';
    const TYPE_USERS = 'USR';
    const TYPE_VAT_CODES = 'VAT';
    const TYPE_VAT_NUMBERS_OF_RELATIONS = 'VATN';
    const TYPE_VAT_GROUPS = 'VTB';
    const TYPE_VAT_GROUPS_COUNTRIES = 'VGM';
    const TYPE_TRANSLATIONS = 'XLT';

	/**
	 * @var string the WSDL location of the finder service
	 */
	private $wsdl = '/webservices/finder.asmx?wsdl';

	/**
	 * @return \SoapClient the finder web service. Used to get all kind of master data from Twinfield.
	 */
	private function getFinder()
	{
		return $this->getClient($this->wsdl);
	}

	/**
	 * @param string $type Finder type, see Finder type.
	 * @param string $pattern The search pattern. May contain wildcards * and ?
	 * @param int $field The search field determines which field or fields will be searched. The available fields
	 * depends on the finder type. Passing a value outside the specified values will cause an error.
	 * @param int $firstRow First row to return, useful for paging
	 * @param int $maxRows Maximum number of rows to return, useful for paging
	 * @param array $options The Finder options. Passing an unsupported name or value causes an error. It's possible to
	 * add multiple options. An option name may be used once, specifying an option multiple times will cause an error.
	 * @return mixed the search results
	 */
	public function searchFinder($type, $pattern = '*', $field = 0, $firstRow = 1, $maxRows = 100, $options = array())
	{
		return $this->getFinder()->Search([
			'type' => $type,
			'pattern' => $pattern,
			'field' => $field,
			'firstRow' => $firstRow,
			'maxRows' => $maxRows,
			'options' => $options,
		]);
	}

}