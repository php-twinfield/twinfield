<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

trait ThreeDimFields
{
    /**
     * @var string
     */
    private $dim1;

    /**
     * @var string
     */
    private $dim2;

    /**
     * @var string
     */
    private $dim3;

    final public function getDim1()
    {
        return $this->dim1;
    }

    /**
     * @param string|null $dim1
     * @return $this
     */
    public function setDim1($dim1 = null)
    {
        $this->dim1 = $dim1;
        return $this;
    }

    final public function getDim2()
    {
        return $this->dim2;
    }

    /**
     * @param string|null $dim2
     * @return $this
     */
    public function setDim2($dim2 = null)
    {
        $this->dim2 = $dim2;
        return $this;
    }

    final public function getDim3()
    {
        return $this->dim3;
    }

    /**
     * @param string|null $dim3
     * @return $this
     */
    public function setDim3($dim3 = null)
    {
        $this->dim3 = $dim3;
        return $this;
    }
}
