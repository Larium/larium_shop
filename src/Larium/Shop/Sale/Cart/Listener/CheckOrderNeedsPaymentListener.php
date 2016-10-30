<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale\Cart\Listener;

use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

class CheckOrderNeedsPaymentListener
{
    public function __invoke(StatefulInterface $order, TransitionEvent $e)
    {
        /**
         * Checks the balance of Order after a `pay` transition.
         * If balance is greater than zero then rollback to `checkout` state to
         * fullfil the payment of the Order.
         */
        if ($order->needsPayment()) {
            $e->getStateMachine()->apply('partial_pay');
        }
    }
}
