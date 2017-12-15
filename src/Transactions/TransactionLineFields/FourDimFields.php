<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

trait FourDimFields
{
    use ThreeDimFields;

    /**
     * @var string
     */
    private $dim4;

    public function getDim4(): ?string
    {
        return $this->dim4;
    }

    /**
     * @param string $dim4
     * @return $this
     */
    public function setDim4(string $dim4)
    {
        $this->dim4 = $dim4;
        return $this;
    }
}