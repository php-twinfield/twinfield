<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\Level1234\DimensionGroupField;
use PhpTwinfield\Fields\Level1234\DimensionTypeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;

/**
 * Class FixedAsset
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class FixedAsset extends BaseObject
{
    use BehaviourField;
    use CodeField;
    use DimensionGroupField;
    use DimensionTypeField;
    use InUseField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UIDField;

    private $financials;
    private $fixedAssets;
    
    public function __construct()
    {
        $this->setTypeFromCode('AST');
        $this->setFinancials(new FixedAssetFinancials);
        $this->setFixedAssets(new FixedAssetFixedAssets);
    }

    public function getFinancials(): FixedAssetFinancials
    {
        return $this->financials;
    }

    public function setFinancials(FixedAssetFinancials $financials)
    {
        $this->financials = $financials;
        return $this;
    }

    public function getFixedAssets(): FixedAssetFixedAssets
    {
        return $this->fixedAssets;
    }

    public function setFixedAssets(FixedAssetFixedAssets $fixedAssets)
    {
        $this->fixedAssets = $fixedAssets;
        return $this;
    }
}