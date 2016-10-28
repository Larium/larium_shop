<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Command;

class CartRemoveItemCommand
{
    public $orderNumber;

    public $identifier;

    public function __construct($identifier, $orderNumber)
    {
        $this->identifier = $identifier;
        $this->orderNumber = $orderNumber;
    }
}
