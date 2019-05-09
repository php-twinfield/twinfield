<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\Level1234\BeginYearField;
use PhpTwinfield\Fields\Level1234\DimensionGroupField;
use PhpTwinfield\Fields\Level1234\DimensionTypeField;
use PhpTwinfield\Fields\Level1234\EndYearField;
use PhpTwinfield\Fields\Level1234\GeneralLedgerBeginPeriodField;
use PhpTwinfield\Fields\Level1234\GeneralLedgerEndPeriodField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;

/**
 * Class GeneralLedger
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class GeneralLedger extends BaseObject
{
    use BeginYearField;
    use BehaviourField;
    use CodeField;
    use DimensionGroupField;
    use DimensionTypeField;
    use EndYearField;
    use GeneralLedgerBeginPeriodField;
    use GeneralLedgerEndPeriodField;
    use InUseField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UIDField;

    private $financials;

    public function __construct()
    {
        $this->setBeginPeriod(0);
        $this->setBeginYear(0);
        $this->setEndPeriod(0);
        $this->setEndYear(0);

        $this->setFinancials(new GeneralLedgerFinancials);
    }

    public function getFinancials(): GeneralLedgerFinancials
    {
        return $this->financials;
    }

    public function setFinancials(GeneralLedgerFinancials $financials)
    {
        $this->financials = $financials;
        return $this;
    }
}
