<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Stub;

class Order
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getItemsTotal()
    {
        return $this->options['item_total'];
    }

    public function getTotalQuantity()
    {
        return $this->options['item_count'];
    }
}
