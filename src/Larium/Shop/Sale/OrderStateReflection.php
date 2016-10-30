<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Sale;

use Finite\State\State;
use Larium\Shop\Sale\Order;
use Larium\Shop\Sale\Cart\Listener\PayOrderListener;
use Larium\Shop\Sale\Cart\Listener\CheckCartIsEmptyListener;
use Larium\Shop\Sale\Cart\Listener\CheckOrderNeedsPaymentListener;

class OrderStateReflection
{
    public function getStates()
    {
        return [
            Order::CART       => ['type' => State::TYPE_INITIAL, 'properties' => []],
            Order::CHECKOUT   => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::PARTIAL_PAID => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::PAID       => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::PROCESSING => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::SENT       => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::DELIVERED  => ['type' => State::TYPE_NORMAL, 'properties' => []],
            Order::CANCELLED  => ['type' => State::TYPE_FINAL, 'properties' => []],
            Order::RETURNED   => ['type' => State::TYPE_FINAL, 'properties' => []],
        ];
    }

    public function getTransitions()
    {
        return [
            'checkout'    => ['from'=>[Order::CART], 'to' => Order::CHECKOUT],
            'partial_pay' => ['from'=>[Order::PAID, Order::PARTIAL_PAID], 'to' => Order::PARTIAL_PAID],
            'pay'         => ['from'=>[Order::CHECKOUT, Order::PARTIAL_PAID], 'to' => Order::PAID],
            'process'     => ['from'=>[Order::PAID], 'to' => Order::PROCESSING],
            'send'        => ['from'=>[Order::PROCESSING], 'to' => Order::SENT],
            'deliver'     => ['from'=>[Order::SENT],'to' => Order::DELIVERED],
            'return'      => ['from'=>[Order::SENT], 'to' => Order::RETURNED],
            'cancel'      => ['from'=>[Order::PAID, Order::PROCESSING], 'to' => Order::CANCELLED],
            'retry'       => ['from'=>[Order::CANCELLED], 'to' => Order::CHECKOUT],
        ];
    }

    public function getCallbacks()
    {
        return [
            'before' => [
                [
                    'from' => Order::CART,
                    'to'   => Order::CHECKOUT,
                    'do'   => new CheckCartIsEmptyListener(),
                ]
            ],
            'after' => [
                [
                    'from' => [Order::CHECKOUT, Order::PARTIAL_PAID],
                    'to'   => Order::PAID,
                    'do'   => new PayOrderListener(),
                ],
                [
                    'from' => [Order::CHECKOUT, Order::PARTIAL_PAID],
                    'to'   => Order::PAID,
                    'do'   => new CheckOrderNeedsPaymentListener(),
                ]
            ]
        ];
    }

    public function getStateConfig()
    {
        return [
            'class' => 'Larium\Shop\Sale\Order',
            'states' => $this->getStates(),
            'transitions' => $this->getTransitions(),
            'callbacks' => $this->getCallbacks(),
        ];
    }
}
