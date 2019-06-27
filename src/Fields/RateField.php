<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Rate;

/**
 * The rate
 * Used by: ActivityProjects, ActivityQuantity, ProjectProjects, ProjectQuantity
 *
 * @package PhpTwinfield\Traits
 */
trait RateField
{
    /**
     * @var Rate|null
     */
    private $rate;

    public function getRate(): ?Rate
    {
        return $this->rate;
    }

    public function getRateToString(): ?string
    {
        if ($this->getRate() != null) {
            return $this->rate->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setRate(?Rate $rate): self
    {
        $this->rate = $rate;
        return $this;
    }
}
