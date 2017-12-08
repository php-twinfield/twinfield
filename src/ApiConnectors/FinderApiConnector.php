<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Enums\Services;
use PhpTwinfield\Services\FinderService;

/**
 * Use the finder web service in order to get all kind of master data from Twinfield.
 *
 * @note Since it is not possible to add the company code to the finder, make sure the correct company is set by using
 * either the SelectCompany function or adding the office option.
 *
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Finder
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package PhpTwinfield
 */
abstract class FinderApiConnector extends BaseApiConnector
{
    /**
     * @var FinderService
     */
    protected $service;

    public const SEARCH_FIELD_CODE_OR_NAME = 0;
    public const SEARCH_FIELD_CODE = 1;
    public const SEARCH_FIELD_NAME = 2;
    public const SEARCH_FIELD_DIMENSIONS_BANK_ACCOUNT_NUMBER = 3;
    public const SEARCH_FIELD_DIMENSIONS_ADDRESS = 4;
    public const SEARCH_FIELD_CUSTOMER_CODE = 3;
    public const SEARCH_FIELD_DIMENSION_CODE_OR_NAME_INVOICE_AMOUNT_INVOICE_NUMBER = 0;

    public const TYPE_ITEMS = 'ART';
    public const TYPE_ASSET_METHODS = 'ASM';
    public const TYPE_BUDGETS = 'BDS';
    public const TYPE_CASHBOOKS_AND_BANKS = 'BNK';
    public const TYPE_BILLING_SCHEDULE = 'BSN';
    public const TYPE_CREDIT_MANAGEMENT_ACTION_CODES = 'CDA';
    public const TYPE_CERTIFICATES = 'CER';
    public const TYPE_LIST_OF_AVAILABLE_CHEQUES = 'CQT';
    public const TYPE_COUNTRIES = 'CTR';
    public const TYPE_CURRENCIES = 'CUR';
    public const TYPE_DIMENSIONS_FINANCIALS = 'DIM';
    public const TYPE_DIMENSIONS_MODIFIED_SINCE_OPTION = 'DIM';
    public const TYPE_DIMENSIONS_PROJECTS = 'DIM';
    public const TYPE_DIMENSION_TYPES = 'DMT';
    public const TYPE_VAT_TYPES = 'DVT';
    public const TYPE_FILTER_MAPPINGS = 'FLT';
    public const TYPE_PAYMENT_FILES = 'FMT';
    public const TYPE_DIMENSION_GROUPS = 'GRP';
    public const TYPE_GATEWAYS = 'GWY';
    public const TYPE_HIERARCHIES = 'HIE';
    public const TYPE_HIERARCHY_NODES = 'HND';
    public const TYPE_INVOICE_TYPES = 'INV';
    public const TYPE_LIST_OF_AVAILABLE_INVOICES = 'IVT';
    public const TYPE_MATCHING_TYPES = 'MAT';
    public const TYPE_OFFICES = 'OFF';
    public const TYPE_OFFICE_GROUPS = 'OFG';
    public const TYPE_AVAILABLE_OFFICES_FOR_INTER_COMPANY_TRANSACTIONS = 'OIC';
    public const TYPE_PAYMENT_TYPES = 'PAY';
    public const TYPE_PAYING_IN_SLIPS = 'PIS';
    public const TYPE_PERIODS = 'PRD';
    public const TYPE_REPORTS = 'REP';
    public const TYPE_WORD_TEMPLATES = 'REW';
    public const TYPE_REMINDER_SCENARIOS = 'RMD';
    public const TYPE_USER_ROLES = 'ROL';
    public const TYPE_SUB_ITEMS = 'SAR';
    public const TYPE_DISTRIBUTION_BY_PERIODS = 'SPM';
    public const TYPE_TAX_GROUPS = 'TXG';
    public const TYPE_TIME_QUANTITIES_TRANSACTION_TYPES = 'TEQ';
    public const TYPE_TRANSACTION_TYPES = 'TRS';
    public const TYPE_TIME_PROJECT_RATES = 'TRT';
    public const TYPE_USERS = 'USR';
    public const TYPE_VAT_CODES = 'VAT';
    public const TYPE_VAT_NUMBERS_OF_RELATIONS = 'VATN';
    public const TYPE_VAT_GROUPS = 'VTB';
    public const TYPE_VAT_GROUPS_COUNTRIES = 'VGM';
    public const TYPE_TRANSLATIONS = 'XLT';

    final protected function getRequiredWebservice(): Services
    {
        return Services::FINDER();
    }
}
