<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Listener;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

class CheckCartIsEmptyListener
{
    public function __invoke(StatefulInterface $order, TransitionEvent $e)
    {
        if ($order->getTotalQuantity() <= 0) {
            throw new \DomainException('Shopping cart is empty');
        }
    }
}
