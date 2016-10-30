<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Listener;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

class PayOrderListener
{
    public function __invoke(StatefulInterface $order, TransitionEvent $e)
    {
        $order->processPayment();
    }
}
