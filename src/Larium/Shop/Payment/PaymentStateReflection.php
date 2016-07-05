<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Shop\Payment;

use Finite\State\State;
use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

class PaymentStateReflection
{
    public function getStates()
    {
        return [
            'unpaid'     => ['type' => State::TYPE_INITIAL, 'properties' => []],
            'pending'    => ['type' => State::TYPE_NORMAL,  'properties' => []],
            'authorized' => ['type' => State::TYPE_NORMAL,  'properties' => []],
            'paid'       => ['type' => State::TYPE_FINAL,   'properties' => []],
            'refunded'   => ['type' => State::TYPE_FINAL,   'properties' => []]
        ];
    }

    public function getTransitions()
    {
        return [
            'purchase'      => ['from'=>['unpaid'], 'to'=>'paid'],
            'doPurchase'    => ['from'=>['pending'], 'to'=>'paid'],
            'doAuthorize'   => ['from'=>['pending'], 'to'=>'authorize'],
            'authorize'     => ['from'=>['unpaid'], 'to'=>'authorized'],
            'capture'       => ['from'=>['authorized'], 'to'=>'paid'],
            'void'          => ['from'=>['authorized'], 'to'=>'refunded'],
            'credit'        => ['from'=>['paid'], 'to'=>'refunded'],
        ];
    }

    public function getCallbacks()
    {
        return [
            'after' => [
                [
                    'from' => ['unpaid', 'pending'],
                    'to'   => 'paid',
                    'do'   => function (StatefulInterface $payment, TransitionEvent $e) {
                        $payment->process($e->getTransition()->getName());
                    }
                ]
            ]
        ];
    }

    public function getStateConfig()
    {
        return [
            'class' => 'Larium\\Shop\\Payment\\Payment',
            'states' => $this->getStates(),
            'transitions' => $this->getTransitions(),
            'callbacks' => $this->getCallbacks(),
        ];
    }
}
