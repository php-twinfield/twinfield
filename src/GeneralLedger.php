<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\Dimensions\BeginPeriodField;
use PhpTwinfield\Fields\Dimensions\BeginYearField;
use PhpTwinfield\Fields\Dimensions\DimensionGroup\GroupField;
use PhpTwinfield\Fields\Dimensions\DimensionType\TypeField;
use PhpTwinfield\Fields\Dimensions\EndPeriodField;
use PhpTwinfield\Fields\Dimensions\EndYearField;
use PhpTwinfield\Fields\InUseField;
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
class GeneralLedger extends BaseObject implements HasCodeInterface
{
    use BeginPeriodField;
    use BeginYearField;
    use BehaviourField;
    use CodeField;
    use EndPeriodField;
    use EndYearField;
    use GroupField;
    use InUseField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use TypeField;
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
    
    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
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
