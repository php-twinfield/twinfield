<?php

namespace Pronamic\Twinfield\Customer;

class CustomerCollectMandate
{
    private $id;
    private $signatureDate;
    private $firstRunDate;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return CustomerCollectMandate
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureDate()
    {
        return $this->signatureDate;
    }

    /**
     * @param string $signatureDate
     * @return CustomerCollectMandate
     */
    public function setSignatureDate($signatureDate)
    {
        $this->signatureDate = $signatureDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstRunDate()
    {
        return $this->firstRunDate;
    }

    /**
     * @param string $firstRunDate
     * @return CustomerCollectMandate
     */
    public function setFirstRunDate($firstRunDate)
    {
        $this->firstRunDate = $firstRunDate;

        return $this;
    }
}
