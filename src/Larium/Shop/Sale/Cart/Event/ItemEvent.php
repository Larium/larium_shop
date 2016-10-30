<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Event;

use Larium\Shop\Sale\Cart;
use Larium\Shop\Sale\OrderItem;
use Larium\Shop\Core\Event\DomainEvent;

abstract class ItemEvent extends DomainEvent
{
    private $cart;

    private $orderItem;

    public function __construct(Cart $cart, OrderItem $orderItem)
    {
        $this->cart = $cart;

        $this->orderItem = $orderItem;
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function getOrderItem()
    {
        return $this->orderItem;
    }
}
