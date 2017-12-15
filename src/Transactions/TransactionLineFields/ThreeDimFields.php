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

    final public function getDim1(): ?string
    {
        return $this->dim1;
    }

    /**
     * @param string|null $dim1
     * @return $this
     */
    public function setDim1(?string $dim1)
    {
        $this->dim1 = $dim1;
        return $this;
    }

    final public function getDim2(): ?string
    {
        return $this->dim2;
    }

    /**
     * @param string|null $dim2
     * @return $this
     */
    public function setDim2(?string $dim2)
    {
        $this->dim2 = $dim2;
        return $this;
    }

    final public function getDim3(): ?string
    {
        return $this->dim3;
    }

    /**
     * @param string|null $dim3
     * @return $this
     */
    public function setDim3(?string $dim3)
    {
        $this->dim3 = $dim3;
        return $this;
    }
}
