<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait FreeTextFields
{
    /**
     * Free text field 1 as entered on the transaction type.
     *
     * @var string|null
     */
    private $freetext1;

    /**
     * Free text field 2 as entered on the transaction type.
     *
     * @var string|null
     */
    private $freetext2;

    /**
     * Free text field 3 as entered on the transaction type.
     *
     * @var string|null
     */
    private $freetext3;

    /**
     * @return null|string
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @param null|string $freetext1
     * @return $this
     */
    public function setFreetext1($freetext1 = null): self
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @param null|string $freetext2
     * @return $this
     */
    public function setFreetext2($freetext2 = null): self
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreetext3()
    {
        return $this->freetext3;
    }

    /**
     * @param null|string $freetext3
     * @return $this
     */
    public function setFreetext3($freetext3 = null): self
    {
        $this->freetext3 = $freetext3;

        return $this;
    }

}