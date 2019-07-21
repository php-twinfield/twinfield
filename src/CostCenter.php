<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\Dimensions\DimensionType\TypeField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;

/**
 * Class CostCenter
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CostCenter extends BaseObject implements HasCodeInterface
{
    use BehaviourField;
    use CodeField;
    use InUseField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use TypeField;
    use UIDField;

    public function __construct()
    {
        $this->setType(\PhpTwinfield\DimensionType::fromCode('KPL'));
    }

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }
}
