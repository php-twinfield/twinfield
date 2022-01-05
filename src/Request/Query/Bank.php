<?php
namespace PhpTwinfield\Request\Query;

use Carbon\Carbon;

/**
 * Used to request (a) bank statement(s) from a certain
 * from and to date range.
 *
 * @package PhpTwinfield
 * @subpackage Request\Query
 * @author Marc van de Geijn <marc@bhosted.nl>
 * @copyright (c) 2022, Marc van de Geijn
 * @version 0.0.1
 */
class Bank extends Query
{
    protected function TYPE(): string {
        return 'GetBankStatements';
    }

    protected function SERVICE(): string {
        return 'http://schemas.datacontract.org/2004/07/Twinfield.WebServices.BankStatementService';
    }


    /**
     * Sets from date and to date if they are present.
     *
     * @access public
     */
    public function __construct($fromDate = null, $toDate = null, $includePostedStatements = true )
    {
        parent::__construct();

        if (null !== $fromDate) {
            $this->setFromDate($fromDate);
        }

        if (null !== $toDate) {
            $this->setToDate($toDate);
        }

        $this->setIncludePostedStatements( $includePostedStatements );
    }

    /**
     * Sets the from date for this bank statement request.
     *
     * @access public
     * @param string $fromDate
     * @return \PhpTwinfield\Request\Read\Bank
     */
    public function setFromDate(Carbon $fromDate): Bank
    {
        $this->add('StatementDateFrom', $fromDate->toDateTimeLocalString());
        return $this;
    }

    /**
     * Sets the from date for this bank statement request.
     *
     * @access public
     * @param string $toDate
     * @return \PhpTwinfield\Request\Read\Bank
     */
    public function setToDate(Carbon $toDate): Bank
    {
        $this->add('StatementDateTo', $toDate->toDateTimeLocalString());
        return $this;
    }

    public function setIncludePostedStatements( bool $include ): Bank
    {
        $this->add('IncludePostedStatements', $include ? "true" : "false" );
        return $this;
    }
}
