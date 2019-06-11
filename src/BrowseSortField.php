<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\Order;
use PhpTwinfield\Fields\CodeField;

class BrowseSortField extends BaseObject
{
    use CodeField;

    /** @var Order|null */
    private $order;

    /**
     * SortField constructor.
     *
     * @param string $code
     * @param null|Order $order
     */
    public function __construct(string $code, ?Order $order = null)
    {
        $this->code = $code;
        $this->order = $order;
    }

    /**
     * @return null|Order
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param null|Order $order
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
}
