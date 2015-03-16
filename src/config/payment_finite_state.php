<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

use Finite\State\State;
use Finite\StatefulInterface;
use Finite\Event\TransitionEvent;

$states = [
    'unpaid'     => ['type' => State::TYPE_INITIAL, 'properties' => []],
    'in_process' => ['type' => State::TYPE_NORMAL,  'properties' => []],
    'authorized' => ['type' => State::TYPE_NORMAL,  'properties' => []],
    'paid'       => ['type' => State::TYPE_FINAL,   'properties' => []],
    'refunded'   => ['type' => State::TYPE_FINAL,   'properties' => []]
];


$transitions = [
    'purchase'      => ['from'=>['unpaid'], 'to'=>'paid'],
    'doPurchase'    => ['from'=>['in_progress'], 'to'=>'paid'],
    'doAuthorize'   => ['from'=>['in_progress'], 'to'=>'authorize'],
    'authorize'     => ['from'=>['unpaid'], 'to'=>'authorized'],
    'capture'       => ['from'=>['authorized'], 'to'=>'paid'],
    'void'          => ['from'=>['authorized'], 'to'=>'refunded'],
    'credit'        => ['from'=>['paid'], 'to'=>'refunded'],
];

$callbacks = [
    'after' => [
        [
            'from' => ['unpaid', 'in_progress'],
            'to'   => 'paid',
            'do'   => function(StatefulInterface $document, TransitionEvent $e) {
                $document->pay($e->getTransition()->getName());
            }
        ]
    ]
];

return array(
    'class' => 'Larium\\Shop\\Payment\\Payment',
    'states' => $states,
    'transitions' => $transitions,
    'callbacks' => $callbacks
);
