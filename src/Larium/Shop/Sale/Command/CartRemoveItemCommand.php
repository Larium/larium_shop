<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Command;

class CartRemoveItemCommand
{
    public $orderNumber;

    public $identifier;

    public $quantity;

    public function __construct($identifier, $quantity, $orderNumber)
    {
        $this->quantity = $quantity;
        $this->identifier = $identifier;
        $this->orderNumber = $orderNumber;
    }
}
