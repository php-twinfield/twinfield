<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\Level1234\DimensionTypeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * Class Project
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Project extends BaseObject
{
    use BehaviourField;
    use CodeField;
    use DimensionTypeField;
    use InUseField;
    use NameField;
    use OfficeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UIDField;
    use VatCodeField;

    private $projects;

    public function __construct()
    {
        $this->setTypeFromCode('PRJ');
        $this->setProjects(new ProjectProjects);
    }

    public function getProjects(): ProjectProjects
    {
        return $this->projects;
    }

    public function setProjects(ProjectProjects $projects)
    {
        $this->projects = $projects;
        return $this;
    }
}