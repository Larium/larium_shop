<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

class CartAddItemCommand
{
    public $orderNumber;

    public $sku;

    public $quantity;

    public function __construct($sku, $quantity, $orderNumber = null)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->orderNumber = $orderNumber;
    }
}
