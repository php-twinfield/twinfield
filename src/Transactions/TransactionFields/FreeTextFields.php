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
    public function getFreetext1(): ?string
    {
        return $this->freetext1;
    }

    /**
     * @param null|string $freetext1
     * @return $this
     */
    public function setFreetext1(?string $freetext1): self
    {
        $this->freetext1 = $freetext1;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreetext2(): ?string
    {
        return $this->freetext2;
    }

    /**
     * @param null|string $freetext2
     * @return $this
     */
    public function setFreetext2(?string $freetext2): self
    {
        $this->freetext2 = $freetext2;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFreetext3(): ?string
    {
        return $this->freetext3;
    }

    /**
     * @param null|string $freetext3
     * @return $this
     */
    public function setFreetext3(?string $freetext3): self
    {
        $this->freetext3 = $freetext3;

        return $this;
    }

}